<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vacancy\BookRequest;
use App\Http\Requests\Vacancy\StoreRequest;
use App\Http\Requests\Vacancy\UnBookRequest;
use App\Http\Requests\Vacancy\UpdateRequest;
use App\Http\Resources\VacancyResource;
use App\Http\Resources\VacancyResourceCollection;
use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class VacancyController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Vacancy::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        if(Auth::user()->role == 'admin' && $request->filled('only_active') && $request->only_active == 'false'){
            $vacancies = Vacancy::all();
        } else {
            $vacancies = Vacancy::all()->whereNotIn('status', ['closed']);
        }

        $data = VacancyResourceCollection::make($vacancies);
        return $this->success($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $organization_id = $request->organization_id;
        $organization = Organization::all()->firstWhere('id', $organization_id);
        if (Auth::id() == $organization->user_id) {
            $vacancy = Vacancy::create($request->validated());
            $data = VacancyResource::make($vacancy);
            return $this->created($data);
        } else {
            throw new AuthorizationException();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vacancy  $vacancy
     * @return JsonResponse
     */
    public function show(Vacancy $vacancy)
    {
        if (Auth::user()->role == 'employer' || Auth::user()->role == 'admin') {
            $vacancy->load('users');
            $data = VacancyResource::make($vacancy);
        } else {
        $data = VacancyResource::make($vacancy);
        }
        return $this->success($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param \App\Models\Vacancy $vacancy
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Vacancy $vacancy)
    {
        $vacancy->update($request->validated());
        $data = VacancyResource::make($vacancy);
        return $this->success($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vacancy  $vacancy
     * @return JsonResponse
     */
    public function destroy(Vacancy $vacancy)
    {
        $vacancy->users()->detach();
        $vacancy->delete();
        return $this->deleted();
    }

    /**
     *
     * @param BookRequest $request
     */
    public function book(BookRequest $request)
    {
        $this->authorize('book',[Vacancy::class, $request]);

        $data = $request->validated();
        $user_id = $data['user_id'];
        $vacancy_id = $data['vacancy_id'];

        $user = User::where('id', $user_id)->first();
        if(!$user){
            throw new ModelNotFoundException('User not found');
        }

        $vacancy = Vacancy::where('id', $vacancy_id)->first();
        if(!$vacancy){
            throw new ModelNotFoundException('Vacancy not found');
        } elseif ($vacancy->status == 'closed') {
            throw new Exception('This vacancy is closed');
        }

        $vacancy->users()->attach($user);

        return $this->booked();
    }

    /**
     *
     * @param UnBookRequest $request
     */
    public function unBook(UnBookRequest $request)
    {
        $this->authorize('unBook',[Vacancy::class, $request]);

        $data = $request->validated();
        $user_id = $data['user_id'];
        $vacancy_id = $data['vacancy_id'];

        $user = User::where('id', $user_id)->first();
        if(!$user){
            throw new ModelNotFoundException('User not found');
        }

        $vacancy = Vacancy::where('id', $vacancy_id)->first();
        if(!$vacancy){
            throw new ModelNotFoundException('Vacancy not found');
        }

        $subscriptions = $user->vacancies()->whereIn('vacancy_id', [$vacancy_id])->count('vacancy_id');
        if(!$subscriptions){
            throw new Exception('You are not subscribed to this vacancy');
        }

        $vacancy->users()->detach($user);

        return $this->unbooked();
    }
}
