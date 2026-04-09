<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EstablecimientoRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            //ingresa si es actualizacion
            return [
                //
                'nombre' => 'sometimes|required|string|max:255|min:4',
                'descripcion' => 'sometimes|required|string|max:5000|min:10',
                'direccion' => 'sometimes|required|string|max:255|min:5',
                'imagen_file' => 'sometimes|required|image|mimes:jpeg,png,jpg,webp|max:2048',
                'telefono' => 'sometimes|nullable|numeric|min:60000000|max:79999999',
                'email' => 'sometimes|nullable|email',
                'website' => 'sometimes|nullable|url',
                'horario_apertura' => 'sometimes|required|date_format:H:i',
                'horario_cierre' => 'sometimes|required|date_format:H:i|after:horario_apertura',
                'latitud' => 'sometimes|required|numeric',
                'longitud' => 'sometimes|required|numeric',
                'categoria_id' => 'sometimes|required|exists:categoria,id',
            ];
        }
        return [
            //
            'nombre' => 'required|string|max:255|min:4',
            'descripcion' => 'required|string|max:5000|min:10',
            'direccion' => 'required|string|max:255|min:5',
            'imagen_file' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'telefono' => 'nullable|numeric|min:60000000|max:79999999',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'horario_apertura' => 'required|date_format:H:i',
            'horario_cierre' => 'required|date_format:H:i|after:horario_apertura',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'categoria_id' => 'required|exists:categoria,id',
        ];
    }

    public function messages()
    {
        return [
            'latitud.required' => 'Por favor Seleccione la ubicación del establecimiento en el mapa.',
            'longitud.required' => 'Por favor Seleccione la ubicación del establecimiento en el mapa.',
        ];
    }
}
