<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RolesRequest extends FormRequest
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
        $id = isset($this->role) ? $this->role->id : '';

        $rules = [
            'name'  => 'required|string|max:255',
            'slug'  => "required|string|max:255|unique:roles,slug,{$id}",
            'types' => 'required|array|exists:types,id'
        ];

        if (isset($this->role) && $this->role->isAdmin()) {
            unset($rules['types']);
        }

        return $rules;
    }
}
