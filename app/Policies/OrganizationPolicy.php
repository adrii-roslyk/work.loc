<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if ($user->role == 'admin'){
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->role == 'employer';
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param  \App\Models\Organization  $organization
     * @return mixed
     */
    public function view(User $user, Organization $organization)
    {
        return $user->id == $organization->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role == 'employer';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param  \App\Models\Organization  $organization
     * @return mixed
     */
    public function update(User $user, Organization $organization)
    {
        return $user->id == $organization->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param  \App\Models\Organization  $organization
     * @return mixed
     */
    public function delete(User $user, Organization $organization)
    {
        return $user->id == $organization->user_id;
    }

    /**
     *
     * @param User $user
     * @return mixed
     */
    public function statsOrganizations()
    {
        return false;
    }
}
