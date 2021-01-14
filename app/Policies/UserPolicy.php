<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param User $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user)
    {
        if ($user->role == 'admin') {
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
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        return $user->id == $model->id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        return $user->id == $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        return $user->id == $model->id;
    }

    /**
     *
     * @param User $user
     * @return mixed
     */
    public function countVacancies(User $user)
    {
        return false;

    }

    /**
     *
     * @param User $user
     * @return mixed
     */
    public function countOrganizations(User $user)
    {
        return false;
    }

    /**
     *
     * @param User $user
     * @return mixed
     */
    public function countUsers(User $user)
    {
        return false;
    }

    /**
     *
     * @param User $user
     * @return mixed
     */
    public function getWorkersOfEachVacancy(User $user)
    {
        return $user->role == 'employer';
    }

    /**
     *
     * @param User $user
     * @return mixed
     */
    public function getWorkersOfEachOrganization(User $user)
    {
        return $user->role == 'employer';
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
