<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSocket implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;
    public string $channel;

    /**
     * Create a new event instance.
     *
     * @param string $message
     * @param string $channel
     *
     */
    public function __construct(string $message, string $channel)
    {
        $this->message = $message;
        $this->channel = $channel;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [$this->channel];
    }

    public function broadcastAs()
    {
        return 'MessageSocket';
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message
        ];
    }
}
