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
        $documents = \App\Models\Document::all();

        Schema::table('documents', function (Blueprint $table) {
            $table->integer('total_net')->change();
            $table->integer('total_vat')->change();
            $table->integer('total')->change();
        });

        $documents->each(function ($document) {
            $document->update([
                'total_net' => $document->total_net * 100,
                'total_vat' => $document->total_vat * 100,
                'total' => $document->total * 100,
            ]);
        });

        $documentRows = \App\Models\DocumentRow::all();

        Schema::table('document_rows', function (Blueprint $table) {
            $table->integer('quantity')->change();
            $table->integer('price')->change();
            $table->integer('vat')->change();
            $table->integer('total')->change();
        });

        $documentRows->each(function ($documentRow) {
            $documentRow->update([
                'price' => $documentRow->price * 100,
                'vat' => $documentRow->vat * 100,
                'total' => $documentRow->total * 100,
            ]);
        });

        $outgoings = \App\Models\Outgoing::all();

        Schema::table('outgoings', function (Blueprint $table) {
            $table->integer('amount')->change();
        });

        $outgoings->each(function ($outgoing) {
            $outgoing->update([
                'amount' => $outgoing->amount * 100,
            ]);
        });

        $payments = \App\Models\Payment::all();

        Schema::table('payments', function (Blueprint $table) {
            $table->integer('amount')->change();
        });

        $payments->each(function ($payment) {
            $payment->update([
                'amount' => $payment->amount * 100,
            ]);
        });

        $vats = \App\Models\Vat::all();

        Schema::table('vats', function (Blueprint $table) {
            $table->integer('value')->change();
        });

        $vats->each(function ($vat) {
            $vat->update([
                'value' => $vat->value * 100,
            ]);
        });

        $workDays = \App\Models\WorkDay::all();

        Schema::table('work_days', function (Blueprint $table) {
            $table->integer('extra_time_cost')->change()->nullable();
            $table->integer('daily_cost')->change()->nullable();
            $table->integer('total_remuneration')->change()->nullable();
            $table->integer('total_extra_cost')->change()->nullable();
            $table->integer('daily_allowance')->change()->nullable();
        });

        $workDays->each(function ($workDay) {
            $workDay->update([
                'extra_time_cost' => $workDay->extra_time_cost ? $workDay->extra_time_cost * 100 : null,
                'daily_cost' => $workDay->daily_cost ? $workDay->daily_cost * 100 : null,
                'total_remuneration' => $workDay->total_remuneration ? $workDay->total_remuneration * 100 : null,
                'total_extra_cost' => $workDay->total_extra_cost ? $workDay->total_extra_cost * 100 : null,
                'daily_allowance' => $workDay->daily_allowance ? $workDay->daily_allowance * 100 : null,
            ]);
        });

        $worksites = \App\Models\Worksite::all();

        Schema::table('worksites', function (Blueprint $table) {
            $table->integer('daily_cost')->change()->nullable();
            $table->integer('extra_time_cost')->change()->nullable();
            $table->integer('daily_allowance')->change()->nullable();
        });

        $worksites->each(function ($worksite) {
            $worksite->update([
                'daily_cost' => $worksite->daily_cost ? $worksite->daily_cost * 100 : null,
                'extra_time_cost' => $worksite->extra_time_cost ? $worksite->extra_time_cost * 100 : null,
                'daily_allowance' => $worksite->daily_allowance ? $worksite->daily_allowance * 100 : null,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->decimal('total_net', 10, 3)->change();
            $table->decimal('total_vat', 10, 3)->change();
            $table->decimal('total', 10, 3)->change();
        });

        Schema::table('document_rows', function (Blueprint $table) {
            $table->decimal('quantity', 10, 3)->change();
            $table->decimal('price', 10, 3)->change();
            $table->decimal('vat', 10, 3)->change();
            $table->decimal('total', 10, 3)->change();
        });

        Schema::table('outgoings', function (Blueprint $table) {
            $table->decimal('amount', 10, 3)->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount', 10, 3)->change();
        });

        Schema::table('vats', function (Blueprint $table) {
            $table->decimal('value', 10, 3)->change();
        });

        Schema::table('work_days', function (Blueprint $table) {
            $table->decimal('extra_time_cost', 10, 3)->change();
            $table->decimal('daily_cost', 10, 3)->change();
            $table->decimal('total_remuneration', 10, 3)->change();
            $table->decimal('total_extra_cost', 10, 3)->change();
            $table->decimal('daily_allowance', 10, 3)->change();
        });

        Schema::table('worksites', function (Blueprint $table) {
            $table->decimal('daily_cost', 10, 3)->change();
            $table->decimal('extra_time_cost', 10, 3)->change();
            $table->decimal('daily_allowance', 10, 3)->change();
        });
    }
};
