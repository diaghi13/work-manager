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
            $table->dropColumn('travel_cost');
            $table->dropColumn('meal_cost');
            $table->dropColumn('extra_cost');
            $table->dropColumn('extra_cost_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_days', function (Blueprint $table) {
            $table->decimal('travel_cost', 9, 3)->nullable();
            $table->decimal('meal_cost', 9, 3)->nullable();
            $table->decimal('extra_cost', 9, 3)->nullable();
            $table->longText('extra_cost_description')->nullable();
        });
    }
};
