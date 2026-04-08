<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            
            // Basic Info
            $table->string('admission_no')->unique()->nullable();
            $table->date('admission_date')->nullable();
            $table->string('registration_no')->nullable();
            
            $table->string('class')->nullable();
            $table->string('section')->nullable();
            $table->string('roll_no')->nullable();
            $table->string('medium')->nullable();
            $table->string('stream')->nullable();
            $table->string('house')->nullable();
            
            $table->string('student_name');
            $table->string('gender')->nullable();
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('place')->nullable();
            $table->string('pin_code')->nullable();
            
            $table->string('nationality')->default('Indian');
            $table->string('physical_status')->nullable();
            $table->string('aadhar_no')->nullable();
            $table->string('papan_no')->nullable();
            $table->string('apaair_id')->nullable();
            $table->string('pend_no')->nullable();
            
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            
            $table->string('blood_group')->nullable();
            $table->string('religion')->nullable();
            $table->string('caste')->nullable();
            $table->string('identity_mark')->nullable();
            $table->string('account_head')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('remarks')->nullable();
            $table->string('photo_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
