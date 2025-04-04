<?php

namespace App\Filament\App\Widgets;

use App\Models\Document;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;

class MonthlyInvoicesChart extends ChartWidget
{
    //protected static ?string $heading = 'dashboard.monthly_revenue_chart.title';
    public function getHeading(): string|Htmlable|null
    {
        return __('app.dashboard.monthly_revenue_chart.title');
    }

    protected function getData(): array
    {
        $currentYear = now()->year;
        $lastYear = now()->subYear()->year;

        return [
            'datasets' => [
                [
                    'label' => __('app.dashboard.monthly_revenue_chart.this_year'),
//                    'data' => Document::whereYear('document_date', 2025)
//                        ->where(function (Builder $query) {
//                            $query->where('type', 'invoice')
//                                ->orWhere('type', 'receipt');
//                        })
//                        ->where('status', '!=', 'draft')
//                        ->get()
//                        ->groupBy(fn ($document) => $document->document_date->format('m'))
//                        ->map(fn ($invoices) => $invoices->sum('total'))
//                        ->values()
//                        ->toArray(),
                    'data' => collect([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11])
                        ->map(fn($month) => Document::whereYear('document_date', $currentYear)
                                ->whereMonth('document_date', $month + 1)
                                ->where(function (Builder $query) {
                                    $query->where('type', 'invoice')
                                        ->orWhere('type', 'receipt');
                                })
                                ->where('status', '!=', 'draft')
                                ->sum('gross_price') / 100)
                        ->toArray(),
                    'pointBackgroundColor' => 'rgb(54, 162, 235)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => __('app.dashboard.monthly_revenue_chart.last_year'),
//                    'data' => Document::whereYear('document_date', 2025)
//                        ->where('type', 'invoice')
//                        ->where('status', '!=', 'draft')
//                        ->get()
//                        ->groupBy(fn ($document) => $document->document_date->format('m'))
//                        ->map(fn ($invoices) => $invoices->sum('total'))
//                        ->values()
//                        ->toArray()
                    'data' => collect([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11])
                        ->map(fn($month) => Document::whereYear('document_date', $lastYear)
                                ->whereMonth('document_date', $month + 1)
                                ->where(function (Builder $query) {
                                    $query->where('type', 'invoice')
                                        ->orWhere('type', 'receipt');
                                })
                                ->where('status', '!=', 'draft')
                                ->sum('gross_price') / 100)
                        ->toArray(),
                    'pointBackgroundColor' => Filament::getTheme(),
                    'borderColor' => 'rgba(0, 0, 0, 0.2)',
                    'backgroundColor' => 'rgba(0, 0, 0, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ]
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
