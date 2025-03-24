<?php

namespace App\Filament\App\Widgets;

use App\Models\Customer;
use App\Models\Document;
use App\Models\Enums\DocumentTypeEnum;
use App\Models\Enums\WorksiteStatusEnum;
use App\Models\Payment;
use App\Models\Worksite;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;

class CustomerOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__('app.dashboard.stats.customers'), Customer::count()),
            Stat::make(
                __('app.dashboard.stats.annual_remuneration'),
                function () {
                    $total = Worksite::whereYear('start_date', now()->year)
                        ->get()
                        ->sum('total_remuneration');

                    return Number::currency($total, 'EUR', 'it_IT');
                }
            ),
            Stat::make(
                __('app.dashboard.stats.annual_works'),
                Worksite::whereYear('start_date', now()->year)
                    ->where('status', WorksiteStatusEnum::ACCEPTED->value)
                    ->orWhere('status', WorksiteStatusEnum::IN_PROGRESS->value)
                    ->orWhere('status', WorksiteStatusEnum::COMPLETED->value)
                    ->orWhere('status', WorksiteStatusEnum::ACTIVE->value)
                    ->count()),
            Stat::make(
                __('app.dashboard.stats.revenue') . ' ' . now()->year,
                function () {
                    $total = Document::whereYear('document_date', now()->year)
                        ->where(function (Builder $query) {
                            $query->where('type', DocumentTypeEnum::INVOICE->value)
                                ->orWhere('type', DocumentTypeEnum::RECEIPT->value);
                        })
                        ->where('status', '!=', 'draft')
                        ->get()
                        ->sum('gross_price');

                    return Number::currency($total, 'EUR', 'it_IT');
                }
            )
            ->description(__('app.dashboard.stats.revenue_previous_year') . ' ' .
                Number::currency(
                    Document::whereYear('document_date', now()->subYear()->year)
                        ->where(function (Builder $query) {
                            $query->where('type', DocumentTypeEnum::INVOICE->value)
                                ->orWhere('type', DocumentTypeEnum::RECEIPT->value);
                        })
                        ->get()
                        ->sum('gross_price'),
                    'EUR',
                    'it_IT')
            ),
            Stat::make(
                __('app.dashboard.stats.credits'),
                function () {
                    $total = Payment::where('payment_date', null)
                        ->where('expiration_date', '<', now())
                        ->get()
                        ->sum('amount');

                    return Number::currency($total, 'EUR', 'it_IT');
                }
            )
        ];
    }
}
