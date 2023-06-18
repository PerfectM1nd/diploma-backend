<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WebRtcCandidateEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $toUserId;
    public $candidate;

    public function __construct($toUserId, $candidate)
    {
        $this->toUserId = $toUserId;
        $this->candidate = $candidate;
        $this->dontBroadcastToCurrentUser();
    }

    public function broadcastOn()
    {
        return new PrivateChannel('webRtc.' . $this->toUserId);
    }

    public function broadcastWith()
    {
        return ['candidate' => $this->candidate];
    }
}
