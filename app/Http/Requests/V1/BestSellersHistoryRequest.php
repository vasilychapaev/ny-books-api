<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class BestSellersHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'author' => ['sometimes', 'string', 'max:255'],
            'isbn' => ['sometimes', 'array'],
            'isbn.*' => ['required', 'string', 'regex:/^(?:\d{9}[\dXx]|\d{13})$/'],
            'title' => ['sometimes', 'string', 'max:255'],
            'offset' => ['sometimes', 'integer', 'min:0', function($attribute, $value, $fail) {
                if ($value % 20 !== 0) {
                    $fail($attribute.' must be a multiple of 20.');
                }
            }],
        ];
    }
} 