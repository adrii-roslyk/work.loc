<?php

namespace App\Policies;

use App\Http\Requests\Vacancy\BookRequest;
use App\Http\Requests\Vacancy\UnBookRequest;
use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\HandlesAuthorization;


class VacancyPolicy
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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Vacancy $vacancy
     * @return mixed
     */
    public function view(User $user, Vacancy $vacancy)
    {
        return true;
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
     * @param Vacancy $vacancy
     * @return mixed
     */
    public function update(User $user, Vacancy $vacancy)
    {
        $organization_id = $vacancy->organization_id;
        $organization = Organization::all()->firstWhere('id', $organization_id);
        return $user->id == $organization->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Vacancy $vacancy
     * @return mixed
     */
    public function delete(User $user, Vacancy $vacancy)
    {
        $organization_id = $vacancy->organization_id;
        $organization = Organization::all()->firstWhere('id', $organization_id);
        return $user->id == $organization->user_id;
    }

    /**
     *
     * @param User $user
     * @param BookRequest $request
     * @return mixed
     */
    public function book(User $user, BookRequest $request)
    {
        $data = $request->validated();
        $user_id = $data['user_id'];

        return $user->id == $user_id && $user->role == 'worker';
    }

    /**
     *
     * @param User $user
     * @param UnBookRequest $request
     * @return mixed
     */
    public function unBook(User $user, UnBookRequest $request)
    {
        $data = $request->validated();
        $user_id = $data['user_id'];
        $vacancy_id = $data['vacancy_id'];

        $vacancy = Vacancy::where('id', $vacancy_id)->first();

        $organization_id = $vacancy->organization_id;
        $organization = Organization::all()->firstWhere('id', $organization_id);
        $creator = $organization->user_id;

        return $user->id == $user_id || $user->id == $creator;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Vacancy $vacancy
     * @return mixed
     */
    public function restore(User $user, Vacancy $vacancy)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Vacancy $vacancy
     * @return mixed
     */
    public function forceDelete(User $user, Vacancy $vacancy)
    {
        //
    }
}
