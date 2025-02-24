<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Document;
use App\Models\Enums\DocumentTypeEnum;
use App\Models\Enums\WorksiteStatusEnum;
use App\Models\Payment;
use App\Models\Worksite;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;

class CustomerOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Clienti', Customer::count()),
            Stat::make(
                'Retribuzione annuale',
                function () {
                    $total = Worksite::whereYear('start_date', now()->year)
                        ->get()
                        ->sum('total_remuneration');

                    return Number::currency($total, 'EUR', 'it_IT');
                }
            ),
            Stat::make(
                'Lavori annuali',
                Worksite::whereYear('start_date', now()->year)
                    ->where('status', WorksiteStatusEnum::ACCEPTED->value)
                    ->orWhere('status', WorksiteStatusEnum::IN_PROGRESS->value)
                    ->orWhere('status', WorksiteStatusEnum::COMPLETED->value)
                    ->orWhere('status', WorksiteStatusEnum::ACTIVE->value)
                    ->count()),
            Stat::make(
                'Fatturato annuale',
                function () {
                    $total = Document::whereYear('document_date', now()->year)
                        ->where(function (Builder $query) {
                            $query->where('type', DocumentTypeEnum::INVOICE->value)
                                ->orWhere('type', DocumentTypeEnum::RECEIPT->value);
                        })
                        ->where('status', '!=', 'draft')
                        ->sum('total');

                    return Number::currency($total, 'EUR', 'it_IT');
                }
            ),
            Stat::make(
                'Crediti',
                function () {
                    $total = Payment::where('payment_date', null)
                        ->where('expiration_date', '<', now())
                        ->sum('amount');

                    return Number::currency($total, 'EUR', 'it_IT');
                }
            )
        ];
    }
}
