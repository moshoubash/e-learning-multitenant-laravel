<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the purge command to run daily at midnight
Schedule::command('app:delete-soft-data')
    ->daily()
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground();

Schedule::command('debugbar:clean')
    ->daily()
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground();

// Reset demo data twice daily (~12h apart) to give trial users a clean slate
Schedule::command('app:reset-demo --preserve-users --force')
    ->twiceDaily(3, 17)
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground();
