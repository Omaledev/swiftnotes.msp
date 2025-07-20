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

class NoteCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The note instance.
     *
     * @var \App\Models\Note
     */
    public $note;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Note $note
     */
    public function __construct(Note $note)
    {
        $this->note = $note->load(['creator', 'team']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('team.' . $this->note->team_id);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'note.created';
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
                'excerpt' => str($this->note->content)->limit(100),
                'created_at' => $this->note->created_at->toDateTimeString(),
                'team_id' => $this->note->team_id,
            ],
            'creator' => [
                'id' => $this->note->creator->id,
                'name' => $this->note->creator->name,
                'avatar' => $this->note->creator->avatar_url ?? null,
            ]
        ];
    }
}
