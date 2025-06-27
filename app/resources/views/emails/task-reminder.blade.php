<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Task Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4a5568;
            color: white;
            padding: 15px;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8fafc;
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .task-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .task-details {
            margin-bottom: 20px;
        }
        .task-details p {
            margin: 5px 0;
        }
        .priority {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .priority-high {
            background-color: #fed7d7;
            color: #c53030;
        }
        .priority-medium {
            background-color: #fefcbf;
            color: #b7791f;
        }
        .priority-low {
            background-color: #c6f6d5;
            color: #2f855a;
        }
        .button {
            display: inline-block;
            background-color: #4a5568;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Task Reminder</h1>
    </div>

    <div class="content">
        <p>Hello {{ $task->user->name }},</p>

        <p>This is a reminder that you have a task due tomorrow:</p>

        <div class="task-details">
            <div class="task-name">{{ $task->name }}</div>

            <p><strong>Description:</strong> {{ $task->description ?: 'No description provided.' }}</p>

            <p>
                <strong>Priority:</strong>
                <span class="priority priority-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
            </p>

            <p><strong>Status:</strong> {{ ucfirst($task->status) }}</p>

            <p><strong>Due Date:</strong> {{ $task->due_date->format('Y-m-d') }}</p>
        </div>

        <p>Please make sure to complete this task before the due date.</p>

        <a href="{{ url('/tasks/' . $task->id) }}" class="button">View Task</a>

        <p style="margin-top: 30px;">
            Regards,<br>
            {{ config('app.name') }} Team
        </p>
    </div>
</body>
</html>
