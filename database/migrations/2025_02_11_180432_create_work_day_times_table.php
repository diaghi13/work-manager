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
        Schema::create('work_day_times', function (Blueprint $table) {
            $table->id();
            $table->timestamp('start_datetime');
            $table->timestamp('end_datetime')->nullable();
            $table->longText('notes')->nullable();
            $table->foreignIdFor(\App\Models\WorkDay::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_day_times');
    }
};
