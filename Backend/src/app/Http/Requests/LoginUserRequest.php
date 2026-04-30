<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Asegúrate de que esto esté en 'true' para permitir la petición
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // CAMBIO AQUÍ: Pedimos 'name' en lugar de 'email'
            'name' => 'required|string',
            'password' => 'required|string',
        ];
    }
}