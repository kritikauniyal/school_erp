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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_no')->unique();
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->integer('capacity')->nullable();
            $table->timestamps();
        });

        Schema::create('bus_stops', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('monthly_charge', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('student_transports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('bus_stop_id')->constrained()->onDelete('cascade');
            $table->foreignId('arrival_vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null');
            $table->foreignId('departure_vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null');
            $table->decimal('monthly_charge', 10, 2);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['Start', 'Stop'])->default('Start');
            $table->string('allotment_no')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_transports');
        Schema::dropIfExists('bus_stops');
        Schema::dropIfExists('vehicles');
    }
};
