<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('ref_number')
                ->nullable();
            $table->date('document_date')
                ->nullable();
            $table->foreignIdFor(\App\Models\Customer::class)
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Worksite::class)
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->decimal('taxable', 10, 3);
            $table->decimal('vat', 10, 3)
                ->nullable();
            $table->decimal('total_amount', 10, 3);
            $table->boolean('invoice')
                ->default(true);
            $table->foreignIdFor(\App\Models\PaymentMethod::class)
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->date('expiry_date')
                ->nullable();
            $table->string('status')
                ->nullable();
            $table->date('payment_date')
                ->nullable();
            $table->string('payment_type'); // acconto, saldo
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
