<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('fee_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('gateway')->nullable();
            $table->string('transaction_id')->nullable();

            $table->decimal('amount',10,2);

            $table->enum('status',['success','failed','pending'])
                  ->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
