<?php

namespace QCod\Gamify\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReputationChanged implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    /**
     * @var Model
     */
    public $userId;

    /**
     * @var int
     */
    public $point;

    /**
     * @var bool
     */
    public $increment;

    /**
     * Create a new event instance.
     *
     * @param $user
     * @param $point integer
     * @param $increment
     */
    public function __construct(Model $user, int $point, bool $increment)
    {
        $this->userId = $user->id;
        $this->point = $point;
        $this->increment = $increment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]
     */
    public function broadcastOn()
    {
        $model = config('gamify.payee_model');

        $user= $model::where('id', $this->userId)->first();

        $channelName = config('gamify.channel_name') . $user->getKey();

        if (config('gamify.broadcast_on_private_channel')) {
            return new PrivateChannel($channelName);
        }

        return new Channel($channelName);
    }
}
