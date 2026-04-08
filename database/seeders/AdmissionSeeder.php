<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admission;

class AdmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admissions = [
            ['date' => '2025-04-15', 'student_name' => 'Rahul Sharma', 'class_name' => 'I', 'fee_collected' => 15000, 'session' => '2025-2026'],
            ['date' => '2025-05-10', 'student_name' => 'Priya Singh', 'class_name' => 'II', 'fee_collected' => 16000, 'session' => '2025-2026'],
            ['date' => '2025-06-05', 'student_name' => 'Amit Kumar', 'class_name' => 'I', 'fee_collected' => 15000, 'session' => '2025-2026'],
            ['date' => '2025-07-20', 'student_name' => 'Sneha Gupta', 'class_name' => 'III', 'fee_collected' => 17000, 'session' => '2025-2026'],
            ['date' => '2025-08-12', 'student_name' => 'Rohan Mehta', 'class_name' => 'II', 'fee_collected' => 16000, 'session' => '2025-2026'],
            ['date' => '2025-09-01', 'student_name' => 'Kavita Yadav', 'class_name' => 'IV', 'fee_collected' => 18000, 'session' => '2025-2026'],
            ['date' => '2025-10-03', 'student_name' => 'Arjun Singh', 'class_name' => 'I', 'fee_collected' => 15000, 'session' => '2025-2026'],
            ['date' => '2025-11-14', 'student_name' => 'Neha Sharma', 'class_name' => 'V', 'fee_collected' => 19000, 'session' => '2025-2026'],
            ['date' => '2025-12-05', 'student_name' => 'Vikas Yadav', 'class_name' => 'III', 'fee_collected' => 17000, 'session' => '2025-2026'],
            ['date' => '2026-01-10', 'student_name' => 'Pooja Gupta', 'class_name' => 'II', 'fee_collected' => 16000, 'session' => '2025-2026'],
            ['date' => '2026-02-18', 'student_name' => 'Rahul Verma', 'class_name' => 'VI', 'fee_collected' => 20000, 'session' => '2025-2026'],
        ];

        foreach ($admissions as $admission) {
            Admission::create($admission);
        }
    }
}
