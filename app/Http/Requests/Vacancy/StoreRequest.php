<?php

namespace App\Http\Requests\Vacancy;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vacancy_name'=>'required|string|max:255',
            'workers_amount'=>'required|numeric|min:1|max:9999',
            'organization_id'=>'required|integer|exists:organizations,id',
            'salary'=>'required|numeric|min:10|max:9999999'
        ];
    }
}
