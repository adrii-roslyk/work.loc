<?php

namespace App\Http\Controllers;

use App\Http\Requests\Organization\StoreRequest;
use App\Http\Requests\Organization\UpdateRequest;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\OrganizationResourceCollection;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        //$this->authorize('viewAny', Organization::class);
        $organizations = Organization::with('user')->get();
        //$organizations = Organization::all();
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
     * @return OrganizationResource
     */
    public function show(Organization $organization)
    {
        $organization->load('user');
        return OrganizationResource::make($organization);
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
        return response()->json($organization);
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
