<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsersRequest extends FormRequest
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
        $id = isset($this->user) ? $this->user->id : '';

        $rules = [
            'role_id'         => 'required|exists:roles,id',
            'name'            => 'required|string',
            'email'           => "required|email|unique:users,email,{$id}",
            'password'        => 'required|min:8',
            'repeat_password' => 'required|same:password'
        ];

        if (!empty($id)) {
            $rules['password'] = 'nullable|min:8';
            $rules['repeat_password'] = 'nullable|same:password';
        }

        return $rules;
    }
}
