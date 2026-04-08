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
        Schema::table('students', function (Blueprint $table) {
            $table->renameColumn('class', 'class_id');
            $table->renameColumn('section', 'section_id');
            $table->renameColumn('pend_no', 'pen_no');
            $table->renameColumn('address_1', 'address');
            
            $table->string('session')->nullable()->after('roll_no');
            $table->boolean('rte_student')->default(false)->after('session');
            $table->string('category')->nullable()->after('caste');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->renameColumn('class_id', 'class');
            $table->renameColumn('section_id', 'section');
            $table->renameColumn('pen_no', 'pend_no');
            $table->renameColumn('address', 'address_1');
            
            $table->dropColumn(['session', 'rte_student', 'category']);
        });
    }
};
