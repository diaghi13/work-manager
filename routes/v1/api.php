<?php

use App\Models\Worksite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware([
    'auth:sanctum',
    \App\Http\Middleware\RegisteredDatabaseHandlerMiddleware::class
])
    ->group(function () {
        Route::get('/worksites', function () {
            return Worksite::all();
        })->name('api.worksites');

        Route::post('/end-workday', function (Request $request) {
            $worksite = Worksite::find($request->input('worksite'));
            $date = \Illuminate\Support\Carbon::parse($request->input('startTime'))->toDateString();

            $startDateTime = \Illuminate\Support\Carbon::parse($request->input('startTime'));
            $endDateTime = \Illuminate\Support\Carbon::parse($request->input('endTime'));

            $startDateTime->setTimezone('Europe/Rome');
            $endDateTime->setTimezone('Europe/Rome');

            if (!$worksite->work_days()->where('date', $date)->exists()) {

                //\Illuminate\Support\Facades\DB::transaction(function () use ($worksite, $request, $date) {
                $workDay = new \App\Models\WorkDay([
                    'type' => \App\Models\Enums\WorkDayTypeEnum::WORK,
                    'date' => $date,
                    'description' => $request->input('description'),
                    'daily_cost' => $worksite->daily_cost,
                    'daily_allowance' => $worksite->daily_allowance,
                ]);

                $newWorkDay = $worksite->work_days()->save($workDay);

                $workDayTime = new \App\Models\WorkDayTime([
                    'start_datetime' => $startDateTime,
                    'end_datetime' => $endDateTime,
                ]);

                $newWorkDay->work_day_datetimes()->save($workDayTime);
                //});
            } else {
                $workDay = $worksite->work_days()->where('date', $date)->first();

                $workDayTime = new \App\Models\WorkDayTime([
                    'start_datetime' => $startDateTime,
                    'end_datetime' => $endDateTime,
                ]);

                $workDay->work_day_datetimes()->save($workDayTime);
            }

            return response()->json(['message' => 'Workday ended']);
        });
    });
