<?php




namespace App\Policies;




use App\Models\MarketingGoal;
use App\Models\User;




class MarketingGoalPolicy
{
    public function view(
        User $user,
        MarketingGoal $goal
    ): bool {
        return $user->isAdmin()
            || $goal->business
                ->users()
                ->where('users.id', $user->id)
                ->exists();
    }




    public function update(
        User $user,
        MarketingGoal $goal
    ): bool {
        if ($user->isAdmin()) {
            return true;
        }




        return $goal->business
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
        MarketingGoal $goal
    ): bool {
        return $this->update($user, $goal);
}
}
