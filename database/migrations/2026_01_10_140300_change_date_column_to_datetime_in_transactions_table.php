<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Drop column date lama
            $table->dropColumn('date');
        });
        
        Schema::table('transactions', function (Blueprint $table) {
            // Tambah column date baru dengan timestamp
            $table->dateTime('date')->after('customer_id')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('date');
        });
        
        Schema::table('transactions', function (Blueprint $table) {
            $table->date('date')->after('customer_id');
        });
    }
};
