<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrainingsRequest extends FormRequest
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
            'name'     => 'required|string|max:255',
            'content'  => 'nullable|string',
            'type_id'  => 'required|integer|exists:types,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB
            'videos.*' => 'nullable|mimes:mp4,mov,ogg|max:65536' // 64MB
        ];
    }

    public function messages()
    {
        $messages = [];

        foreach ($this->file('videos', []) as $key => $file) {
            $fileName = $file->getClientOriginalName();

            $messages["videos.{$key}.uploaded"] = __('validation.uploaded', ['attribute' => $fileName]);
            $messages["videos.{$key}.max"] = __('validation.max.file', ['attribute' => $fileName]);
            $messages["videos.{$key}.mimes"] = __('validation.mimes', ['attribute' => $fileName]);
        }

        foreach ($this->file('images', []) as $key => $file) {
            $fileName = $file->getClientOriginalName();

            $messages["images.{$key}.uploaded"] = __('validation.uploaded', ['attribute' => $fileName]);
            $messages["images.{$key}.image"] = __('validation.image', ['attribute' => $fileName]);
            $messages["images.{$key}.max"] = __('validation.max.file', ['attribute' => $fileName]);
            $messages["images.{$key}.mimes"] = __('validation.mimes', ['attribute' => $fileName]);
        }

        return $messages;
    }
}
