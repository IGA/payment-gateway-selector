<?php

use App\Jobs\UpdatePosRatesJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new UpdatePosRatesJob)
    ->dailyAt('23:59')
    ->timezone('Europe/Istanbul')
    ->withoutOverlapping();

//Schedule::job(new UpdatePosRatesJob)
//    ->everyMinute()
//    ->withoutOverlapping();