<?php

namespace App\Http\Resources;

use App\Models\Organization;
use Illuminate\Http\Resources\Json\JsonResource;

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
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'city'=>$this->city,
            'country'=>$this->country,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'creator'=>new UserResource($this->whenLoaded('user'))
        ];
    }
}
