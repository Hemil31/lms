<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:50',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:16',
            'password_confirm' => 'required|same:password',
        ];
    }

    /*
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => __('validation.required'),
            'name.string'=> __('validation.string'),
            'name.max'=> __('validation.max'),
            'email.required'=> __('validation.required'),
            'email.email'=> __('validation.email'),
            'email.unique'=> __('validation.unique'),
            'password.required'=>__('validation.required'),
            'password.max'=> __('validation.max'),
            'password.min'=> __('validation.min'),
            'password_confirm.required'=>__('validation.required'),
            'password_confirm.same'=>__('validation.same')
        ];
    }

}
