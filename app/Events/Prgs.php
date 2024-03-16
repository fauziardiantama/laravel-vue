<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Prgs implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $type;
    public $for;
    public $item;
    public $isEmpty;

    /**
     * Create a new event instance.
     */
    public function __construct($type = "update", $for = ["Admin"], $isEmpty = false,$item)
    {
        $this->type = $type;
        $this->for = $for;
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
        return array_map(function($channel){
            return new PrivateChannel($channel);
        }, $this->for);
    }

    public function broadcastWith()
    {
        return [
            'type' => $this->type,
            'isEmpty' => $this->isEmpty,
            'item' => $this->item
        ];
    }
}
