<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;

foreach (Student::with(['user', 'class'])->get() as $s) {
    echo "ID: {$s->id} | Name: " . ($s->user->name ?? 'N/A') . " | Class: " . ($s->class->name ?? 'N/A') . PHP_EOL;
}
