<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registration_students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id');
            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
            $table->string('name');
            $table->string('class');
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            
            // Previous School fields
            $table->string('previous_school')->nullable();
            $table->string('board')->nullable();
            $table->string('last_exam_percentage')->nullable();
            
            // Test & Form Info fields
            $table->date('test_date')->nullable();
            $table->time('test_time')->nullable();
            $table->string('test_venue')->nullable();
            $table->string('full_marks')->nullable();
            $table->string('pass_marks')->nullable();
            $table->string('percentage')->nullable();
            $table->string('obtained_marks')->nullable();
            $table->string('obtained_percentage')->nullable();
            $table->string('prospectus_no')->nullable();
            $table->string('admission_form_no')->nullable();
            $table->string('voucher_receipt_no')->nullable();
            $table->string('prospectus_fee')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_students');
    }
};
