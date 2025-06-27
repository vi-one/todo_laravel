<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Mail\TaskReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendTaskReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for tasks due in 1 day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $tasks = Task::with('user')
            ->whereDate('due_date', $tomorrow)
            ->where('status', '!=', 'done')
            ->get();

        $this->info("Found {$tasks->count()} tasks due tomorrow.");

        foreach ($tasks as $task) {
            if ($task->user && $task->user->email) {
                $this->info("Sending reminder for task '{$task->name}' to {$task->user->email}");

                Mail::to($task->user->email)
                    ->queue(new TaskReminderMail($task));
            }
        }

        return Command::SUCCESS;
    }
}
