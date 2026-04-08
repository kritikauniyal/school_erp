<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HostelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $boysHostel = \App\Models\Hostel::create([
            'name' => 'Ganga Boys Hostel',
            'type' => 'Boys',
            'address' => 'Near East Gate',
            'intake' => 100
        ]);

        $girlsHostel = \App\Models\Hostel::create([
            'name' => 'Yamuna Girls Hostel',
            'type' => 'Girls',
            'address' => 'Near West Gate',
            'intake' => 100
        ]);

        for ($i = 101; $i <= 110; $i++) {
            \App\Models\HostelRoom::create([
                'hostel_id' => $boysHostel->id,
                'room_no' => (string) $i,
                'type' => 'Boys',
                'capacity' => 4,
                'description' => 'Standard Boys Room'
            ]);

            \App\Models\HostelRoom::create([
                'hostel_id' => $girlsHostel->id,
                'room_no' => (string) ($i + 100),
                'type' => 'Girls',
                'capacity' => 4,
                'description' => 'Standard Girls Room'
            ]);
        }
    }
}
