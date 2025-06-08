<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SeatHeld implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $showtimeId;
    public $seat;
    public $userId;
    public $heldUntil;

    /**
     * Create a new event instance.
     */
    public function __construct($showtimeId, $seat, $userId, $heldUntil)
    {
        $this->showtimeId = $showtimeId;
        $this->seat = $seat;
        $this->userId = $userId;
        $this->heldUntil = $heldUntil;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('showtime.' . $this->showtimeId);
    }
}

class SeatReleased implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $showtimeId;
    public $seat;

    public function __construct($showtimeId, $seat)
    {
        $this->showtimeId = $showtimeId;
        $this->seat = $seat;
    }

    public function broadcastOn()
    {
        return new Channel('showtime.' . $this->showtimeId);
    }
}