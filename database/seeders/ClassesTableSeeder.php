<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SchoolClass;

class ClassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            'Nursery', 'LKG', 'UKG',
            '1st', '2nd', '3rd', '4th', '5th',
            '6th', '7th', '8th', '9th', '10th',
            '11th', '12th'
        ];

        foreach ($classes as $className) {
            SchoolClass::updateOrCreate(
                ['name' => $className]
            );
        }
    }
}
