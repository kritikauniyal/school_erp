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
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('enq_no')->unique();
            $table->string('name');
            $table->string('mobile');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->date('date');
            $table->date('followup_date')->nullable();
            $table->string('reference')->nullable();
            $table->string('for')->default('admission'); // 'admission' or 'other'
            $table->string('class')->nullable();
            $table->integer('no_of_child')->nullable();
            $table->string('other')->nullable();
            $table->string('status')->default('Pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
