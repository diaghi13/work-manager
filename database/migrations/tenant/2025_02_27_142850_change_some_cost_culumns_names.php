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
        Schema::table('documents', function (Blueprint $table) {
            $table->renameColumn('total_net', 'net_price');
            $table->renameColumn('total_vat', 'vat_price');
            $table->renameColumn('total', 'gross_price');
        });

        Schema::table('document_rows', function (Blueprint $table) {
            $table->renameColumn('price', 'net_price');
            $table->renameColumn('vat', 'vat_price');
            $table->integer('total_price')
                ->nullable();
            $table->renameColumn('total', 'gross_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->renameColumn('net_price', 'total_net');
            $table->renameColumn('vat_price', 'total_vat');
            $table->renameColumn('gross_price', 'total');
        });

        Schema::table('document_rows', function (Blueprint $table) {
            $table->renameColumn('net_price', 'price');
            $table->renameColumn('vat_price', 'vat');
            $table->dropColumn('total_price');
            $table->renameColumn('gross_price', 'total');
        });
    }
};
