<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class BookCreateRequest extends FormRequest
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
            'title' => 'required|string|max:150',
            'author' => 'required|string|max:100',
            'publication_date' => 'date',
            'status' => 'required',
        ];

    }

    public function messages(): array
    {
        return [
            'title.required' => trans('validation.required'),
            'title.string' => trans('validation.string'),
            'title.max' => trans('validation.max'),
            'author.required' => trans('validation.required'),
            'author.string' => trans('validation.string'),
            'author.max' => trans('validation.max'),
            'publication_date.date' => trans('validation.date'),
            'status.required' => trans('validation.required'),
        ];
    }
}
