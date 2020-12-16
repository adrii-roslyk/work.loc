<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 * @mixin User
 */
class UserResource extends JsonResource
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
            'role'=>$this->role,
            'email'=>$this->email,
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name,
            'country'=>$this->country,
            'city'=>$this->city,
            'phone'=>$this->phone,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at
        ];
    }
}
