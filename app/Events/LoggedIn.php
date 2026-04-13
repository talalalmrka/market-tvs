<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LoggedIn
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $guard;

    public $user;

    public $remember;

    public $sessionId;

    /**
     * Create a new event instance.
     */
    public function __construct($guard, $user, $remember, $sessionId)
    {
        $this->guard = $guard;
        $this->user = $user;
        $this->remember = $remember;
        $this->sessionId = $sessionId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('auth'),
        ];
    }
}
