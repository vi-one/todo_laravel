<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'name',
        'description',
        'priority',
        'status',
        'due_date',
        'user_id',
        'sync_with_google_calendar',
        'google_calendar_event_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        'sync_with_google_calendar' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shareableLinks()
    {
        return $this->hasMany(ShareableLink::class);
    }

    public function histories()
    {
        return $this->hasMany(TaskHistory::class);
    }

    /**
     * Sync task with Google Calendar
     */
    public function syncWithGoogleCalendar()
    {
        if (!$this->sync_with_google_calendar) {
            // If sync is disabled but we have an event ID, delete the event
            if ($this->google_calendar_event_id) {
                $this->deleteGoogleCalendarEvent();
            }
            return;
        }

        if ($this->google_calendar_event_id) {
            $this->updateGoogleCalendarEvent();
        } else {
            $this->createGoogleCalendarEvent();
        }
    }

    /**
     * Create a Google Calendar event for this task
     */
    protected function createGoogleCalendarEvent()
    {
        try {
            $event = \Spatie\GoogleCalendar\Event::create([
                'name' => $this->name,
                'description' => $this->description,
                'startDateTime' => $this->due_date,
                'endDateTime' => $this->due_date->addHour(),
            ]);

            $this->google_calendar_event_id = $event->id;
            $this->save();
        } catch (\Exception $e) {
            \Log::error('Failed to create Google Calendar event: ' . $e->getMessage());
        }
    }

    /**
     * Update the Google Calendar event for this task
     */
    protected function updateGoogleCalendarEvent()
    {
        try {
            $event = \Spatie\GoogleCalendar\Event::find($this->google_calendar_event_id);

            if ($event) {
                $event->name = $this->name;
                $event->description = $this->description;
                $event->startDateTime = $this->due_date;
                $event->endDateTime = $this->due_date->addHour();
                $event->save();
            } else {
                // If event not found, create a new one
                $this->google_calendar_event_id = null;
                $this->createGoogleCalendarEvent();
            }
        } catch (\Exception $e) {
            \Log::error('Failed to update Google Calendar event: ' . $e->getMessage());
        }
    }

    /**
     * Delete the Google Calendar event for this task
     */
    public function deleteGoogleCalendarEvent()
    {
        if (!$this->google_calendar_event_id) {
            return;
        }

        try {
            $event = \Spatie\GoogleCalendar\Event::find($this->google_calendar_event_id);

            if ($event) {
                $event->delete();
            }

            $this->google_calendar_event_id = null;
            $this->save();
        } catch (\Exception $e) {
            \Log::error('Failed to delete Google Calendar event: ' . $e->getMessage());
        }
    }
}
