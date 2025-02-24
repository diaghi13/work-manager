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
        Schema::create('document_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Document::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->nullableMorphs('productable');
            $table->longText('description')->nullable();
            $table->foreignIdFor(\App\Models\MeasureUnit::class)
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->decimal('quantity')->nullable();
            $table->decimal('price', 10, 3)->nullable();
            $table->foreignIdFor(\App\Models\Vat::class)
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->decimal('vat', 10, 3)->nullable();
            $table->decimal('total', 10, 3)->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_rows');
    }
};
