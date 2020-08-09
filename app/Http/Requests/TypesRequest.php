<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TypesRequest extends FormRequest
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
        $id = isset($this->type) ? $this->type->id : '';

        return [
            'slug' => "required|string|unique:types,slug,{$id}",
            'name' => 'required|string'
        ];
    }
}
