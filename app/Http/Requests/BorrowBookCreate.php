<?php

namespace App\Http\Requests;

use App\Enums\BookStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class BorrowBookCreate extends FormRequest
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
            'book_id' => [
                'required',
                'string',
                'uuid',
                'exists:books,uuid',
                function ($attribute, $value, $fail) {
                    if (!\Illuminate\Support\Str::isUuid($value)) {
                        $fail(__('validation.uuid'));
                        return;
                    }
                    $book = \App\Models\Book::where('uuid', $value)->first();
                    if ($book && $book->status !== BookStatusEnum::Available) {
                        $fail(__('book.already_borrowed'));
                    }
                },
            ],
            'due_date' => 'required|date|after_or_equal:today',
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
            'book_id.required' => __('validation.required'),
            'book_id.string' => __('validation.string'),
            'book_id.exists' => __('validation.exists'),
            'due_date.required' => __('validation.required'),
            'due_date' => __('validation.date'),
            'due_date.after_or_equal' => __('validation.after_or_equal'),
        ];
    }
}
