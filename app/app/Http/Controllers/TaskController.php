<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->tasks();

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by due date
        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        // Filter by due date range
        if ($request->filled('due_date_from')) {
            $query->whereDate('due_date', '>=', $request->due_date_from);
        }

        if ($request->filled('due_date_to')) {
            $query->whereDate('due_date', '<=', $request->due_date_to);
        }

        $tasks = $query->orderBy('due_date', 'asc')->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:to-do,in progress,done',
            'due_date' => 'required|date',
        ]);

        // Add Google Calendar sync flag if present
        if ($request->has('sync_with_google_calendar')) {
            $validated['sync_with_google_calendar'] = true;
        }

        $task = Auth::user()->tasks()->create($validated);

        // Sync with Google Calendar if needed
        if ($task->sync_with_google_calendar) {
            $task->syncWithGoogleCalendar();
        }

        // Record task creation in history
        TaskHistory::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'changes' => $task->toArray(),
            'change_type' => 'create',
        ]);

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);
        $histories = $task->histories()->orderBy('created_at', 'desc')->get();

        return view('tasks.show', compact('task', 'histories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);

        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:to-do,in progress,done',
            'due_date' => 'required|date',
        ]);

        $oldData = $task->toArray();

        // Handle Google Calendar sync flag
        $validated['sync_with_google_calendar'] = $request->has('sync_with_google_calendar');

        $task->update($validated);

        // Sync with Google Calendar
        $task->syncWithGoogleCalendar();

        // Record task update in history
        TaskHistory::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'changes' => [
                'old' => $oldData,
                'new' => $task->toArray(),
            ],
            'change_type' => 'update',
        ]);

        return redirect()->route('tasks.show', $task->id)
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);
        $taskData = $task->toArray();

        // Delete Google Calendar event if exists
        if ($task->google_calendar_event_id) {
            $task->deleteGoogleCalendarEvent();
        }

        // Record task deletion in history before deleting the task
        TaskHistory::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'changes' => $taskData,
            'change_type' => 'delete',
        ]);

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    /**
     * Display a historical version of the task.
     */
    public function showHistory(string $taskId, string $historyId)
    {
        $task = Auth::user()->tasks()->findOrFail($taskId);
        $history = TaskHistory::where('task_id', $taskId)->findOrFail($historyId);

        // Create a task object with historical data
        $historicalTask = new Task();

        if ($history->change_type === 'update') {
            // For updates, use the 'new' data
            $data = $history->changes['new'];
        } else {
            // For create or delete, use the data directly
            $data = $history->changes;
        }

        // Set the task attributes from historical data
        $historicalTask->fill($data);

        // Set non-fillable attributes
        $historicalTask->id = isset($data['id']) ? $data['id'] : $task->id;
        $historicalTask->created_at = isset($data['created_at']) ? \Carbon\Carbon::parse($data['created_at']) : $task->created_at;
        $historicalTask->updated_at = isset($data['updated_at']) ? \Carbon\Carbon::parse($data['updated_at']) : $history->created_at;

        // Convert due_date string to Carbon instance if needed
        if (isset($data['due_date']) && is_string($data['due_date'])) {
            $historicalTask->due_date = \Carbon\Carbon::parse($data['due_date']);
        }

        return view('tasks.history', [
            'task' => $task,
            'historicalTask' => $historicalTask,
            'history' => $history
        ]);
    }
}
