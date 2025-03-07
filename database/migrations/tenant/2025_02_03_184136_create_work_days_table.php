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
        Schema::create('work_days', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->timestamp('start_date_time');
            $table->timestamp('end_date_time');
            $table->decimal('travel_cost')->nullable();
            $table->decimal('meal_cost')->nullable();
            $table->decimal('extra_cost')->nullable();
            $table->longText('extra_cost_description')->nullable();
            $table->boolean('calculate_extra_cost')->default(false);
            $table->foreignIdFor(\App\Models\Worksite::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_days');
    }
};
