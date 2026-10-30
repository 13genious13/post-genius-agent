<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\User;

class BusinessPolicy
{
    public function view(User $user, Business $business): bool
    {
        return $user->isAdmin()
            || $business->users()
                ->where('users.id', $user->id)
                ->exists();
    }

    public function update(User $user, Business $business): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $business->users()
            ->where('users.id', $user->id)
            ->wherePivotIn('role', ['owner', 'admin'])
            ->exists();
    }

    public function delete(User $user, Business $business): bool
    {
        return $this->update($user, $business);
 }
}
