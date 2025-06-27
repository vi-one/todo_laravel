<?php

namespace App\Http\Controllers;

use App\Models\ShareableLink;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShareableLinkController extends Controller
{

    /**
     * Store a newly created shareable link in storage.
     */
    public function store(Request $request, Task $task)
    {
        // Check if the task belongs to the authenticated user
        if ($task->user_id !== Auth::id()) {
            return redirect()->route('tasks.index')
                ->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'expires_at' => 'required|date|after:now',
        ]);

        // Delete any existing shareable links for this task
        $task->shareableLinks()->delete();

        // Create a new shareable link
        $shareableLink = $task->shareableLinks()->create([
            'token' => ShareableLink::generateToken(),
            'expires_at' => $validated['expires_at'],
        ]);

        return redirect()->route('tasks.show', $task->id)
            ->with('success', 'Shareable link created successfully.')
            ->with('shareableLink', route('shared-task.show', $shareableLink->token));
    }

    /**
     * Remove the specified shareable link from storage.
     */
    public function destroy(ShareableLink $shareableLink)
    {
        // Check if the task belongs to the authenticated user
        if ($shareableLink->task->user_id !== Auth::id()) {
            return redirect()->route('tasks.index')
                ->with('error', 'Unauthorized action.');
        }

        $shareableLink->delete();

        return redirect()->route('tasks.show', $shareableLink->task_id)
            ->with('success', 'Shareable link deleted successfully.');
    }

    /**
     * Display the specified task via a shareable link.
     */
    public function show($token)
    {
        $shareableLink = ShareableLink::where('token', $token)->firstOrFail();

        // Check if the link has expired
        if ($shareableLink->isExpired()) {
            return view('shared-task.expired');
        }

        $task = $shareableLink->task;

        return view('shared-task.show', compact('task', 'shareableLink'));
    }
}
