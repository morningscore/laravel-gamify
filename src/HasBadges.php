<?php

namespace QCod\Gamify;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \QCod\Gamify\Badge> $badges
 * @property-read int|null $badges_count
 */
trait HasBadges
{
    /**
     * Badges user relation
     *
     * @return mixed
     */
    public function badges()
    {
        return $this->belongsToMany(config('gamify.badge_model'), 'user_badges')
            ->withTimestamps();
    }

    /**
     * Sync badges for qiven user
     *
     * @param $user
     */
    public function syncBadges($user = null)
    {
        $user = is_null($user) ? $this : $user;

        $badgeIds = [];

        $badges = app('badges')->sort(function($a, $b) {
            return ($a->getLevel() < $b->getLevel()) ? -1 : 1;
        });

        $syncAgain = false;

        foreach ($badges as $badge) {
            if ($syncAgain)
                break;

            $qualifier = $badge->qualifier($user);
            if ($qualifier) {
                array_push($badgeIds, $badge->getBadgeId());

                if ($qualifier === 2)
                    $syncAgain = true;
            }
        }

        $user->badges()->sync($badgeIds);

        if ($syncAgain) $this->syncBadges($user);
    }
}
