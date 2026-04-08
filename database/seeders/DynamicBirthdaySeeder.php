<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;

class DynamicBirthdaySeeder extends Seeder
{
    public function run(): void
    {
        // Add random students if not enough exist
        if (Student::count() < 3) {
            for ($i = 0; $i < 3; $i++) {
                Student::create([
                    'student_name' => 'Test Student ' . $i,
                    'class' => 'V',
                    'section' => 'A',
                    'roll_no' => $i + 1
                ]);
            }
        }
        
        $students = Student::take(3)->get();
        if ($students->count() >= 3) {
            $students[0]->update(['dob' => Carbon::now()->subYears(10)]); // Today
            $students[1]->update(['dob' => Carbon::now()->addDay()->subYears(10)]); // Tomorrow
            $students[2]->update(['dob' => Carbon::now()->addDays(5)->subYears(10)]); // In 5 days
        }

        // Add dummy employees if not enough exist
        if (User::count() < 2) {
            for ($i = 0; $i < 2; $i++) {
                User::create([
                    'name' => 'Employee ' . $i,
                    'email' => "employee{$i}@school.com",
                    'password' => bcrypt('password')
                ]);
            }
        }

        $users = User::take(2)->get();
        if ($users->count() >= 2) {
            $users[0]->update(['dob' => Carbon::now()->subYears(30)]); // Today
            $users[1]->update(['dob' => Carbon::now()->addDays(2)->subYears(25)]); // In 2 days
        }
    }
}
