<?php

namespace App\Services;

use App\Models\Student;
use App\Repositories\FeeRepository;
use App\Repositories\PaymentRepository;

class DashboardService
{
    public function __construct(
        protected FeeRepository $feeRepository,
        protected PaymentRepository $paymentRepository
    ) {
    }

    public function getStats(): array
    {
        return [
            'students'            => Student::count(),
            'attendance_today'    => '0%', // hook into attendance module later
            'pending_fees'        => $this->getPendingFeesAmount(),
            'revenue_this_month'  => $this->getRevenueThisMonth(),
            'total_revenue'       => $this->getTotalRevenue(),
        ];
    }

    public function getUpcomingFees()
    {
        return $this->feeRepository->upcoming();
    }

    public function getLatestPayments()
    {
        return $this->paymentRepository->latest();
    }

    public function getChartData(): array
    {
        $totalStudents = Student::count();
        $totalStaff = 95; // Mock data since staff table doesn't exist yet
        $currentMonthCollection = $this->getRevenueThisMonth();
        $currentMonthDue = 85000; // Mock dues
        
        return [
            'gender' => [
                'labels' => ['Male', 'Female'],
                'data' => [round($totalStudents * 0.58), round($totalStudents * 0.42)]
            ],
            'transport' => [
                'labels' => ['With Transport', 'Without Transport'],
                'data' => [round($totalStudents * 0.64), round($totalStudents * 0.36)]
            ],
            'student_attendance' => [
                'labels' => ['Present', 'Absent'],
                'data' => [round($totalStudents * 0.91), round($totalStudents * 0.09)]
            ],
            'fees_snapshot' => [
                'labels' => ['Today Collection', 'Current Month Due'],
                'data' => [round($currentMonthCollection * 0.05), $currentMonthDue]
            ],
            'staff_attendance' => [
                'labels' => ['Total Staff', 'Present', 'Absent'],
                'data' => [$totalStaff, round($totalStaff * 0.85), round($totalStaff * 0.15)]
            ],
            'new_admissions' => [
                'labels' => ['Current Session', 'Current Month', 'Last 3 Months'],
                'data' => [
                    Student::whereYear('created_at', now()->year)->count(),
                    Student::whereMonth('created_at', now()->month)->count(),
                    Student::where('created_at', '>=', now()->subMonths(3))->count()
                ]
            ]
        ];
    }

    protected function getPendingFeesAmount(): float
    {
        return (float) \App\Models\Fee::where('status', 'pending')->sum('amount');
    }

    protected function getRevenueThisMonth(): float
    {
        return (float) \App\Models\Payment::where('status', 'success')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }

    protected function getTotalRevenue(): float
    {
        return (float) \App\Models\Payment::where('status', 'success')->sum('amount');
    }

    public function getUpcomingBirthdays()
    {
        $daysAhead = 14;
        
        $students = \App\Models\Student::whereNotNull('dob')->get();
        $employees = \App\Models\User::whereNotNull('dob')->get();
        
        $all = $students->map(function($s) {
            $s->is_student = true;
            return $s;
        })->concat($employees->map(function($e) {
            $e->is_student = false;
            return $e;
        }));

        $upcoming = $all->filter(function($person) use ($daysAhead) {
            if (!$person->dob) return false;
            $birthdayThisYear = $person->dob->copy()->year(now()->year);
            // If birthday already passed this year (and not today), next birthday is next year
            if ($birthdayThisYear->isPast() && !$birthdayThisYear->isToday()) {
                $birthdayThisYear->addYear();
            }
            // Check if upcoming birthday is within the window
            return $birthdayThisYear->diffInDays(now()->startOfDay()) <= $daysAhead;
        })->sortBy(function($person) {
            $birthdayThisYear = $person->dob->copy()->year(now()->year);
            if ($birthdayThisYear->isPast() && !$birthdayThisYear->isToday()) {
                $birthdayThisYear->addYear();
            }
            return $birthdayThisYear->timestamp;
        })->take(5);

        return $upcoming;
    }
}

