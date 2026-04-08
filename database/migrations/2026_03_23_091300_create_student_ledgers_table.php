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
        Schema::create('student_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            // 'Fee', 'Payment', 'Reversal', 'Refund', 'Discount'
            $table->string('transaction_type', 50);
            $table->decimal('amount', 10, 2);
            $table->decimal('balance', 10, 2); // Running balance snapshot
            $table->string('reference_type')->nullable(); // e.g., 'App\\Models\\Payment', 'App\\Models\\FeeDemand'
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('description', 255)->nullable();
            $table->string('session', 50)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            // Indexes for faster balance rebuilding
            $table->index(['student_id', 'date', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_ledgers');
    }
};
