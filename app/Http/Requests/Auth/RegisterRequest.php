<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'=>'required|string|email|unique:users,email|max:100',
            'password'=>'required|string|min:6|max:20',
            'first_name'=>'sometimes|string|min:2|max:20',
            'last_name'=>'sometimes|string|min:2|max:40',
            'country'=>'sometimes|string|max:100',
            'city'=>'sometimes|string|max:100',
            'phone'=>'sometimes|string|max:30',
            'role'=>'sometimes|exists:roles,name|not_in:admin'
        ];
    }
}
