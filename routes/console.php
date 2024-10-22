<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Facade Schedule
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

//Schedule::command('app:files')->everyMinute(); 

//Ejecución de comando a la hora requerida
Schedule::command('app:files')->dailyAt('22:00');