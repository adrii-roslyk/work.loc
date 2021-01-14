<?php

namespace App\Http\Controllers;

use App\Http\Requests\Organization\StoreRequest;
use App\Http\Requests\Organization\UpdateRequest;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\OrganizationResourceCollection;
use App\Http\Resources\UserResourceCollection;
use App\Http\Resources\VacancyResourceCollection;
use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Organization::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        if(Auth::user()->role == 'employer'){
            $organizations = Auth::user()->organizations()->with('user')->get();
        } else {
            $organizations = Organization::with('user')->get();
        }

        $data = OrganizationResourceCollection::make($organizations);
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
        $organization = Organization::create($request->validated());
        return response()->json($organization, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Organization  $organization
     * @return JsonResponse
     */
    public function show(Organization $organization, Request $request)
    {
        $request->whenHas('vacancies', function ($input) use ($organization){
            switch (true) {
                case ($input == 0):
                    break;
                case ($input == 1):
                    $vacancy_ids = $organization->vacancies()->get()
                        ->where('status', 'active')
                        ->map(function ($item){
                            return $item->id;
                        });
                    $organization->load(['vacancies' => function ($query) use ($vacancy_ids){
                        $query->whereIN('id', $vacancy_ids);
                    }]);
                    break;
                case ($input == 2):
                    $vacancy_ids = $organization->vacancies()->get()
                        ->where('status', 'closed')
                        ->map(function ($item){
                            return $item->id;
                        });
                    $organization->load(['vacancies' => function ($query) use ($vacancy_ids){
                        $query->whereIN('id', $vacancy_ids);
                    }]);
                    break;
                case ($input == 3):
                    $organization->load('vacancies');
            }
        });

        if ($request->filled('workers')) {
            $data = $request->whenHas('workers', function ($input) use ($organization, $request) {
                switch (true) {
                    case ($input == 0):
                        $data = OrganizationResource::make($organization);
                        return $data;
                        break;
                    case ($input == 1):
                        if($request->filled('vacancies') && $request->vacancies == 1) {
                            $users_ids = $organization->vacancies()->get()
                                ->where('status', 'active')->map(function ($item){
                                    $data = $item->users()->get()->map(function ($item){
                                        return $item->id;
                                    });
                                    return $data;
                                });
                        } elseif ($request->filled('vacancies') && $request->vacancies == 2) {
                            $users_ids = $organization->vacancies()->get()
                                ->where('status', 'closed')->map(function ($item){
                                    $data = $item->users()->get()->map(function ($item){
                                        return $item->id;
                                    });
                                    return $data;
                                });
                        } else {
                            $users_ids = $organization->vacancies()->get()
                                ->map(function ($item){
                                    $data = $item->users()->get()->map(function ($item){
                                        return $item->id;
                                    });
                                    return $data;
                                });
                        }

                        $workers_id = $users_ids->collapse()->all();
                        $workers = User::whereIn('id', $workers_id)->get();

                        $data = collect();
                        $data->put('id', $organization->id);
                        $data->put('title', $organization->title);
                        $data->put('city', $organization->city);
                        $data->put('country', $organization->country);
                        $data->put('created_at', $organization->created_at);
                        $data->put('updated_at', $organization->updated_at);

                        if($request->filled('vacancies') && $request->vacancies != 0){
                            $data->put('vacancies', VacancyResourceCollection::make($organization->vacancies));
                        }

                        $data->put('workers', $workers);

                        return $data;
                }
























            });
        } else {
            $data = OrganizationResource::make($organization);
        }




//       if ($workers) {
//           $data = null;
//       } else {
//           $data = OrganizationResource::make($organization);
//       }

//        $data = collect();
//        $data->push($organization);
//
//        //$data = $workers;
//

        //$data = OrganizationResource::make($organization);
        return $this->success($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param \App\Models\Organization $organization
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Organization $organization)
    {
        $organization->update($request->validated());
        $data = OrganizationResource::make($organization);
        return $this->success($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Organization  $organization
     * @return JsonResponse
     */
    public function destroy(Organization $organization)
    {
        $organization->vacancies()->get()->each(function ($item){
            $item->users()->detach();
            $item->delete();
        });
        $organization->delete();
        return $this->deleted();
    }
}
