<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'location_id' => ['required', 'exists:locations,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'wifi_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comfort_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:5', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'Por favor, selecione uma nota de 1 a 5 estrelas.',
            'comment.min' => 'Escreva pelo menos 5 caracteres no comentário para ajudar a comunidade.',
        ];
    }
}
