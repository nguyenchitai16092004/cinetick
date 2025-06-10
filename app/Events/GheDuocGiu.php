<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GheDuocGiu implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ma_ghe, $suat_chieu_id, $user_id, $hold_until, $type;

    public function __construct($ma_ghe, $suat_chieu_id, $user_id, $hold_until, $type = 'hold')
    {
        $this->ma_ghe = $ma_ghe;
        $this->suat_chieu_id = $suat_chieu_id;
        $this->user_id = $user_id;
        $this->hold_until = $hold_until;
        $this->type = $type;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('ghe.' . $this->suat_chieu_id);
    }

    // Nếu muốn đặt tên event rõ ràng ở frontend:
    public function broadcastAs()
    {
        return 'GheDuocGiu';
    }

    // Nếu muốn kiểm soát dữ liệu trả về:
    public function broadcastWith()
    {
        return [
            'ma_ghe' => $this->ma_ghe,
            'suat_chieu_id' => $this->suat_chieu_id,
            'user_id' => $this->user_id,
            'hold_until' => $this->hold_until,
            'type' => $this->type,
        ];
    }
}