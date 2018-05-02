<?php

namespace App\Policies;

use App\Models\Admin;
use App\User;
use App\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before(Admin $user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }


    public function lists(Admin $user)
    {
        return $user->check('view', User::class);
    }

    public function view(Admin $user, User $u)
    {
        return $user->check('view', $u);
    }


    public function create(Admin $user)
    {
        return $user->check('create', User::class);
    }


    public function update(Admin $user, User $u)
    {
        return $user->check('update', $u);
    }

    public function delete(Admin $user, User $u)
    {
        return $user->check('delete', $u);
    }
}
