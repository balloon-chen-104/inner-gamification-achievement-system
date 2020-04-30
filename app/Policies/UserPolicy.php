<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        return $user->active_group != NULL;
    }
    /**
     * Determine whether the user has active group.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->active_group != NULL;
    }

    /**
     * Determine whether the user is authority in the group.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAuthority(User $user)
    {
        foreach($user->groups as $group) {
            if($group->id == $user->active_group && $group->pivot->authority == 1){
                return true;
            }
        }
        return false;
    }
}
