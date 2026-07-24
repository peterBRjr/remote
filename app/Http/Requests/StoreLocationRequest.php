<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'category' => ['required', 'in:cafe,coworking,library,hotel_lobby'],
            'wifi_speed_mbps' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'noise_level' => ['required', 'in:quiet,moderate,lively'],
            'outlet_density' => ['required', 'in:scarce,moderate,abundant'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image_url' => ['nullable', 'url', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do estabelecimento é obrigatório.',
            'address.required' => 'O endereço completo é necessário para calcular a geolocalização.',
            'category.in' => 'Selecione uma categoria válida (café, coworking, biblioteca ou lobby de hotel).',
        ];
    }
}
