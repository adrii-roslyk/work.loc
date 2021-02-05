<?php

namespace App\Http\Resources;

use App\Models\Organization;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * Class OrganizationResource
 * @package App\Http\Resources
 * @mixin Organization
 */
class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'city'=>$this->city,
            'country'=>$this->country,
            'created_at'=>$this->created_at->format('Y-m-d H:i:s'),
            'updated_at'=>$this->updated_at->format('Y-m-d H:i:s'),
            'creator'=>new UserResource($this->whenLoaded('user')),
            'vacancies'=>VacancyResource::collection($this->whenLoaded('vacancies')),
            'workers'=>$this->when($this->workers, $this->workers)
        ];
    }
}
