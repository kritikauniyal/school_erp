<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['student_name', 'class_name']);

            // Add new pipeline columns
            $table->unsignedBigInteger('registration_id')->nullable()->after('id');
            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
            
            $table->unsignedBigInteger('registration_student_id')->nullable()->after('registration_id');
            $table->foreign('registration_student_id')->references('id')->on('registration_students')->onDelete('cascade');
            
            $table->string('admission_no')->unique()->nullable()->after('registration_student_id');
            
            // Adjust status default to the new pipeline phase
            $table->string('status')->default('Pending Payment')->change();
        });
    }

    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            if (Schema::hasColumn('admissions', 'registration_id')) {
                $table->dropForeign(['registration_id']);
                $table->dropColumn('registration_id');
            }
            if (Schema::hasColumn('admissions', 'registration_student_id')) {
                $table->dropForeign(['registration_student_id']);
                $table->dropColumn('registration_student_id');
            }
            if (Schema::hasColumn('admissions', 'admission_no')) {
                $table->dropColumn('admission_no');
            }
            
            $table->string('student_name')->nullable();
            $table->string('class_name')->nullable();
            
            $table->string('status')->default('Admitted')->change();
        });
    }
};
