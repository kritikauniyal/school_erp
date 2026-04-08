<?php

namespace App\Repositories;

use App\Models\Payment;
use Carbon\Carbon;

class PaymentRepository extends BaseRepository
{
    public function __construct(Payment $payment)
    {
        parent::__construct($payment);
    }

    public function latest(int $limit = 10)
    {
        return $this->model->newQuery()
            ->with(['student.user'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function monthlyRevenue(int $months = 6): array
    {
        $now = Carbon::now();
        $labels = [];
        $values = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $labels[] = $month->format('M Y');

            $values[] = $this->model->newQuery()
                ->where('status', 'success')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');
        }

        return compact('labels', 'values');
    }
}

