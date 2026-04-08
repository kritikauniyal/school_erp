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
        Schema::create('student_previous_schools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            
            $table->string('school_name')->nullable();
            $table->string('previous_class')->nullable();
            $table->string('tc_no')->nullable();
            
            // Immunizations JSON or flags
            $table->boolean('bcg')->default(false);
            $table->boolean('opv')->default(false);
            $table->boolean('opv_booster')->default(false);
            $table->boolean('mmr')->default(false);
            $table->boolean('dpt')->default(false);
            $table->boolean('dpt_booster')->default(false);
            $table->boolean('measles')->default(false);
            $table->boolean('thyroid')->default(false);
            $table->boolean('hepatitis_b')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_previous_schools');
    }
};
