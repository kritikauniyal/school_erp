<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\LateFine;

foreach (LateFine::all() as $f) {
    echo "Month: {$f->month} | Classes: " . implode(',', $f->classes) . " | Amount: {$f->amount} | To Date: " . $f->to_date->format('Y-m-d') . PHP_EOL;
}
