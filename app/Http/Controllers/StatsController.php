<?php

namespace App\Http\Controllers;

use App\Models\Organization;
//use App\Models\Stats;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResourceCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


class StatsController extends Controller
{
    /**
     *
     * @return JsonResponse
     */
    public function countVacancies()
    {
        $this->authorize('countVacancies', User::class);

        $data = collect();
        $data->put('active', Vacancy::all()->where('status', 'active')->count());
        $data->put('closed', Vacancy::all()->where('status', 'closed')->count());
        $data->put('all', Vacancy::all()->count());
        return $this->success($data);
    }

    /**
     *
     * @return JsonResponse
     */
    public function countOrganizations()
    {
        $this->authorize('countOrganizations', User::class);

        $data = collect();
        $data->put('all', Organization::withTrashed()->count());
        $data->put('active', Organization::withTrashed()->whereNull('deleted_at')->count());
        $data->put('softDelete', Organization::withTrashed()->whereNotNull('deleted_at')->count());
        return $this->success($data);
    }

    /**
     *
     * @return JsonResponse
     */
    public function countUsers()
    {
        $this->authorize('countUsers', User::class);

        $roles = User::all()->groupBy('role')->map(function ($item){
            return count($item);
        });
        return $this->success($roles);
    }

}
