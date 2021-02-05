<?php

namespace App\Policies;

use App\Http\Requests\Vacancy\BookRequest;
use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Exception;

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
        return Organization::find(request()->organization_id)->user_id == $user->id && $user->role == 'employer';
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
        // у каждой вакансии есть атрибут organization, поэтому получить организацию
        // через отношение $vacancy->organization не получается

        $organization_id = $vacancy->organization_id;
        $organization = Organization::where('id', $organization_id)->first();
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
        return $this->update($user, $vacancy);
    }

    /**
     *
     * @param User $user
     * @param BookRequest $request
     * @return mixed
     */
    public function book(User $user)
    {
        $user_id = request()->user_id;
        $vacancy_id = request()->vacancy_id;

        $vacancy = Vacancy::findOrFail($vacancy_id);
        if ($vacancy->status == 'closed') {
            return Response::deny('This vacancy is closed');
            //throw new Exception('This vacancy is closed');
        }

        if ($vacancy->users()->find($user_id)) {
            return Response::deny('You have already subscribed to this vacancy');
            //throw new Exception('You have already subscribed to this vacancy');
        }

        return  $user->id == request()->user_id && $user->role == 'worker';
    }

    /**
     *
     * @param User $user
     * @param BookRequest $request
     * @return mixed
     */
    public function unBook(User $user)
    {
        $user_id = request()->user_id;
        $vacancy_id = request()->vacancy_id;
        //$user = User::findOrFail($user_id);
        $vacancy = Vacancy::findOrFail($vacancy_id);

        if (!$vacancy->users()->find($user_id)) {
            return Response::deny('You are not subscribed to this vacancy');
            //throw new Exception('You are not subscribed to this vacancy');
        }

        $organization_id = $vacancy->organization_id;
        $organization = Organization::where('id', $organization_id)->first();
        $creator = $organization->user_id;

        return $user->id == $user_id || $user->id == $creator;
    }

    /**
     *
     * @param User $user
     * @return mixed
     */
    public function statsVacancies()
    {
        return false;
    }
}
