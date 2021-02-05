<?php

namespace App\Http\Resources;

use App\Models\Vacancy;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class VacancyResource
 * @package App\Http\Resources
 * @mixin Vacancy
 */

class VacancyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'status'=>$this->status,
            'vacancy_name'=>$this->vacancy_name,
            'workers_amount'=>$this->workers_amount,
            'workers_booked'=>$this->workers_booked,
            'organization'=>$this->organization,
            'salary'=>$this->salary,
            'created_at'=>$this->created_at->format('Y-m-d H:i:s'),
            'updated_at'=>$this->updated_at->format('Y-m-d H:i:s'),
            'workers'=>UserResource::collection($this->whenLoaded('users'))
        ];
    }
}
