<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vacancy\BookRequest;
use App\Http\Requests\Vacancy\StoreRequest;
use App\Http\Requests\Vacancy\UpdateRequest;
use App\Http\Resources\VacancyResource;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
        if(Auth::user()->role == 'admin' && $request->filled('only_active') && $request->only_active == 'true'){
            $vacancies = Vacancy::all()->whereIn('status', ['active']);
        } else {
            $vacancies = Vacancy::all();
        }

        $data = VacancyResource::collection($vacancies);
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
        $vacancy = Vacancy::create($request->validated());
        $data = VacancyResource::make($vacancy);
        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vacancy  $vacancy
     * @return JsonResponse
     */
    public function show(Vacancy $vacancy)
    {
        $hasVacancies = Auth::user()->hasVacancies;
        $isTheCreator = $hasVacancies->firstWhere('id', $vacancy->id);

        if (Auth::user()->role == 'employer' && $isTheCreator || Auth::user()->role == 'admin') {
            $vacancy->load('users');
        }

        $data = VacancyResource::make($vacancy);
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
        $vacancy->delete();
        return $this->deleted();
    }

    /**
     * Підписатися на вакансію
     *
     * @param BookRequest $request
     */
    public function book(BookRequest $request)
    {
        $this->authorize(Vacancy::class);

        $data = $request->validated();
        $user_id = $data['user_id'];
        $vacancy_id = $data['vacancy_id'];

        $user = User::where('id', $user_id)->first();
        $vacancy = Vacancy::where('id', $vacancy_id)->first();

        $vacancy->users()->attach($user);
        return $this->success();
    }

    /**
     * Відписатися від вакансії
     *
     * @param BookRequest $request
     */
    public function unBook(BookRequest $request)
    {
        $this->authorize(Vacancy::class);

        $data = $request->validated();
        $user_id = $data['user_id'];
        $vacancy_id = $data['vacancy_id'];

        $user = User::where('id', $user_id)->first();
        $vacancy = Vacancy::where('id', $vacancy_id)->first();

        $vacancy->users()->detach($user);
        return $this->success();
    }
}
