<?php

namespace QCod\Gamify\Listeners;

use QCod\Gamify\Events\ReputationChanged;

class SyncBadges
{
    /**
     * Handle the event.
     *
     * @param  ReputationChanged  $event
     * @return void
     */
    public function handle(ReputationChanged $event)
    {
        $model = config('gamify.payee_model');

        $user= $model::where('id', $event->userId)->first();

        $user->syncBadges();
    }
}
