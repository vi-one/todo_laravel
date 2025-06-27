<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('tasks:send-reminders')->dailyAt('8:00');
