<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$student = App\Models\Student::first();
if ($student) {
    echo "Found student: " . $student->student_name . "\n";
    echo "Class ID: " . $student->class_id . "\n";
    echo "Section ID: " . $student->section_id . "\n";
} else {
    echo "No students found.\n";
}
