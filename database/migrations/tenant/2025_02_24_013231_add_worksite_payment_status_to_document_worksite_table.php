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
        Schema::table('document_worksite', function (Blueprint $table) {
            $table->tinyInteger('worksite_payment_status')
                ->default(0)
                ->after('worksite_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_worksite', function (Blueprint $table) {
            $table->dropColumn('worksite_payment_status');
        });
    }
};
