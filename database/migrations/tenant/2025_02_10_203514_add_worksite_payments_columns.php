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
        Schema::table('worksites', function (Blueprint $table) {
            $table->decimal('daily_cost')->nullable();
            $table->decimal('extra_time_cost')->nullable();
            $table->float('daily_hours', 2)->nullable();
            $table->decimal('daily_allowance')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('worksites', function (Blueprint $table) {
            $table->dropColumn('daily_cost');
            $table->dropColumn('extra_time_cost');
            $table->dropColumn('daily_hours');
            $table->dropColumn('daily_allowance');
        });
    }
};
