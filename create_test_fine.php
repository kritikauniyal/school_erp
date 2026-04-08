<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\LateFine;

LateFine::create([
    'classes' => ['2nd'],
    'month' => 'February',
    'from_date' => '2026-02-01',
    'to_date' => '2026-02-10',
    'amount' => 50,
    'is_active' => true,
]);

echo "Test late fine created for February, Class 2nd." . PHP_EOL;
