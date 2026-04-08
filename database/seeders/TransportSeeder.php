<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Vehicle::create(['vehicle_no' => 'Bus 1', 'driver_name' => 'John Doe', 'driver_phone' => '1234567890', 'capacity' => 40]);
        \App\Models\Vehicle::create(['vehicle_no' => 'Bus 2', 'driver_name' => 'Jane Smith', 'driver_phone' => '0987654321', 'capacity' => 30]);

        \App\Models\BusStop::create(['name' => 'Gola Road', 'monthly_charge' => 1000]);
        \App\Models\BusStop::create(['name' => 'Frazer Road', 'monthly_charge' => 1500]);
        \App\Models\BusStop::create(['name' => 'Kankarbagh', 'monthly_charge' => 1200]);
    }
}
