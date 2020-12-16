<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vacancy\StoreRequest;
use App\Http\Requests\Vacancy\UpdateRequest;
use App\Http\Resources\VacancyResource;
use App\Http\Resources\VacancyResourceCollection;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $vacancies = Vacancy::all();
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
        $vacancy = Vacancy::create($request->validated());
        return response()->json($vacancy, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vacancy  $vacancy
     * @return VacancyResource
     */
    public function show(Vacancy $vacancy)
    {
        //$vacancy->load('organization');
        return VacancyResource::make($vacancy);
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
        return response()->json($vacancy);
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
}
