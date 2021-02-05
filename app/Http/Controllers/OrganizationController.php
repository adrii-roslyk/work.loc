<?php

namespace App\Http\Controllers;

use App\Http\Requests\Organization\StoreRequest;
use App\Http\Requests\Organization\UpdateRequest;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\UserResource;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        $data = OrganizationResource::collection($organizations);
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
        $validated_data = $request->validated();
        $organization = Organization::create($validated_data);
        $organization->load('user');
        $data = OrganizationResource::make($organization);

        return $this->created($data);
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
            switch ($input) {
                case (0):
                    break;
                case (1):
                    $vacancy_ids = $organization->vacancies()->get()
                        ->where('status', 'active')
                        ->map(function ($item){
                            return $item->id;
                        });
                    $organization->load(['vacancies' => function ($query) use ($vacancy_ids){
                        $query->whereIN('id', $vacancy_ids);
                    }]);
                    break;
                case (2):
                    $vacancy_ids = $organization->vacancies()->get()
                        ->where('status', 'closed')
                        ->map(function ($item){
                            return $item->id;
                        });
                    $organization->load(['vacancies' => function ($query) use ($vacancy_ids){
                        $query->whereIN('id', $vacancy_ids);
                    }]);
                    break;
                case (3):
                    $organization->load('vacancies');
            }
        });

        $request->whenHas('workers', function ($input) use ($organization, $request){
            switch ($input) {
                case (0):
                    break;
                case (1):
                    $users_ids = $organization->vacancies()->get()
                        ->map(function ($item){
                            $data = $item->users()->get()->map(function ($item){
                                return $item->id;
                            });
                            return $data;
                        });

                    $workers_id = $users_ids->collapse()->unique();
                    $workers = User::whereIn('id', $workers_id)->get();
                    $organization->workers = UserResource::collection($workers);
            }
        });

        $data = OrganizationResource::make($organization);
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
        $organization->load('user');
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
        $organization->delete();
        return $this->deleted();
    }
}
