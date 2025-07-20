<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Note;
use App\Models\User;

class UserEditingNote implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The note being edited.
     *
     * @var \App\Models\Note
     */
    public $note;

    /**
     * The user editing the note.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Note $note
     * @param \App\Models\User $user
     */
    public function __construct(Note $note, User $user)
    {
        $this->note = $note;
        $this->user = $user->makeVisible(['name', 'email']); // Only expose necessary fields
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('note.' . $this->note->id);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'user.editing';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'note' => [
                'id' => $this->note->id,
                'title' => $this->note->title,
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar' => $this->user->avatar_url ?? null,
                'editing_since' => now()->toDateTimeString(),
            ],
            'event_time' => now()->toIso8601String(),
        ];
    }
}
