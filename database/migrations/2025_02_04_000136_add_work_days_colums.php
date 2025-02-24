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
        Schema::table('work_days', function (Blueprint $table) {
            $table->decimal('total_hours')->nullable();
            $table->decimal('extra_time')->nullable();
            $table->decimal('extra_time_cost')->nullable();
            $table->decimal('daily_cost')->nullable();
            $table->decimal('total_remuneration')->nullable();
            $table->decimal('total_extra_cost')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_days', function (Blueprint $table) {
            $table->dropColumn('total_hours');
            $table->dropColumn('extra_time');
            $table->dropColumn('extra_time_cost');
            $table->dropColumn('daily_cost');
            $table->dropColumn('total_remuneration');
            $table->dropColumn('total_extra_cost');
        });
    }
};
