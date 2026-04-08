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
        Schema::create('student_parents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            
            $table->string('father_name')->nullable();
            $table->string('father_qualification')->nullable();
            $table->string('father_income')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_aadhar')->nullable();
            
            $table->string('mother_name')->nullable();
            $table->string('mother_qualification')->nullable();
            $table->string('mother_income')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_aadhar')->nullable();
            
            $table->string('guardian_name')->nullable();
            $table->string('guardian_qualification')->nullable();
            $table->string('guardian_aadhar')->nullable();
            
            $table->integer('no_of_children')->nullable();
            $table->boolean('is_only_child')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_parents');
    }
};
