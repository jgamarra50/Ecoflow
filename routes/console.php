<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule telemetry updates
use Illuminate\Support\Facades\Schedule;

Schedule::command('telemetry:update --active-only')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();

