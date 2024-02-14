<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\DokumenRegistrasi;

class ProposalUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $item;
    public $isEmpty;
    public $user;
    /**
     * Create a new event instance.
     */
    public function __construct($user, $isEmpty, $item)
    {
        $this->user = $user;
        $this->isEmpty = $isEmpty;
        $this->item = $item;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('proposal-ta');
    }

    public function broadcastWith()
    {
        return [
            'token' => $this->user,
            'isEmpty' => $this->isEmpty,
            'item' => $this->item
        ];
    }
}
