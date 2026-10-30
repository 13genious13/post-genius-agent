<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;

class CampaignPolicy
{
    public function view(
        User $user,
        Campaign $campaign
    ): bool {
        return $user->isAdmin()
            || $campaign->business
                ->users()
                ->where('users.id', $user->id)
                ->exists();
    }




    public function update(
        User $user,
        Campaign $campaign
    ): bool {
        if ($user->isAdmin()) {
            return true;
        }




        return $campaign->business
            ->users()
            ->where('users.id', $user->id)
            ->wherePivotIn(
                'role',
                ['owner', 'admin']
            )
            ->exists();
    }




    public function delete(
        User $user,
        Campaign $campaign
    ): bool {
        return $this->update($user, $campaign);
    }
}
