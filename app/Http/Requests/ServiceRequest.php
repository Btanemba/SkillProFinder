<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'person_id' => 'required|exists:people,id',
            'name' => 'required|string|min:2|max:255',
            'years_of_experience' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|min:10',
            'skill_level' => 'required|in:beginner,intermediate,advanced,expert',
            'certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'sample_pictures' => 'nullable|array',
            'sample_pictures.*' => 'file|mimes:jpg,jpeg,png,gif|max:5120',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
