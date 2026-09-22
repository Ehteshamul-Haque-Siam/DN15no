<?php

use Illuminate\Support\Facades\Schedule;

// Auto-verify pending bKash payments every 5 minutes
Schedule::command('bkash:poll')->everyFiveMinutes();

// Daily database backup at 3 AM
Schedule::command('db:backup')->dailyAt('03:00');