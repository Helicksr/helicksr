<?php

namespace App\Listeners;

use App\Events\LickShared as LickSharedEvent;
use App\Notifications\LickShared as LickSharedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendLickSharedNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    { }

    /**
     * Handle the event.
     */
    public function handle(LickSharedEvent $event): void
    {
        // prepare list of unique users to avoid sending multiple notifications
        $usersFromTeams = $event->targetTeams->reduce(
            fn ($users, $team) => $team->allUsers()->keyBy('id')->union($users),
            collect(),
        );

        $usersToNotify = $event->targetUsers->keyBy('id')->union($usersFromTeams);

        $usersToNotify->each(
            fn ($targetUser) => Notification::send(
                $targetUser,
                new LickSharedNotification($event->lick, $event->sourceUser, $targetUser),
            ),
        );
    }
}
