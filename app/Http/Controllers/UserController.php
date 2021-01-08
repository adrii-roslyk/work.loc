<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Exception;
use http\Env\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Void_;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $users = User::all();

        switch (true) {
            case ($request->filled('first_name')):
                $users = User::where('first_name', $request->first_name)->get();
                break;
            case ($request->filled('last_name')):
                $users = User::where('last_name', $request->last_name)->get();
                break;
            case ($request->filled('city')):
                $users = User::where('city', $request->city)->get();
                break;
            case ($request->filled('country')):
                $users = User::where('country', $request->country)->get();
        }

        $data = UserResourceCollection::make($users);
        return $this->success($data);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user)
    {
        //$this->authorize('view', $user);
        $data = UserResource::make($user);
        return $this->success($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, User $user)
    {
        $user->update($request->validated());
        $data = UserResource::make($user);

        return $this->success($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(User $user){

        $user->delete();
        return $this->deleted();
    }

    /**
     *
     * @return JsonResponse
     */
    public function getWorkersOfEachVacancy()
    {
        $this->authorize('getWorkersOfEachVacancy', User::class);

        $vacancies = Auth::user()->hasVacancies()->get();
        $users = $vacancies->map(function ($item){
            $users = collect();
            $users->put('vacancy_name', $item->vacancy_name);
            $data = UserResourceCollection::make($item->users()->get());
            $users->put('workers', $data);
            return $users;
        });

        return $this->success($users);
    }

    /**
     *
     * @return JsonResponse
     */
    public function getWorkersOfEachOrganization()
    {
        $this->authorize('getWorkersOfEachOrganization', User::class);

        $organizations = Auth::user()->organizations()->get();
        $users = $organizations->map(function ($item){

            $users = collect();
            $users->put('organization', $item->title);
            $vacancies = $item->vacancies()->get();

            $data = $vacancies->map(function ($item){

                $data = [];
                $data['vacancy_name'] = $item->vacancy_name;
                $data['workers'] = UserResourceCollection::make($item->users()->get());
                return $data;
            });

            foreach ($data as $value){

                $users->put("vacancy: {$value['vacancy_name']}, workers", $value['workers'] );
            }

            return $users;
        });

        return $this->success($users);
    }
}
