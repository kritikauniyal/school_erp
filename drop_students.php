<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

Schema::disableForeignKeyConstraints();
DB::statement('DROP TABLE IF EXISTS student_fees');
DB::statement('DROP TABLE IF EXISTS student_previous_schools');
DB::statement('DROP TABLE IF EXISTS student_parents');
DB::statement('DROP TABLE IF EXISTS students');
// Remove old migrations from migrations table so migrate:refresh doesn't fail
DB::table('migrations')->where('migration', 'like', '%create_students_table')->delete();
Schema::enableForeignKeyConstraints();

echo "Dropped old tables.\n";
