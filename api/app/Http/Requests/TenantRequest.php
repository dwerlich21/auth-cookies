<?php

namespace App\Http\Requests;

class TenantRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:tenants,slug'],
            'logo' => ['nullable', 'string'],
            'primary_color' => ['nullable', 'string', 'max:7'],
            'secondary_color' => ['nullable', 'string', 'max:7'],
            'active' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute é obrigatório',
            'string' => ':attribute deve ser um texto',
            'max' => ':attribute não pode ter mais que :max caracteres',
            'unique' => ':attribute já está em uso',
            'boolean' => ':attribute deve ser verdadeiro ou falso',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'Nome',
            'slug' => 'Identificador',
            'logo' => 'Logo',
            'primary_color' => 'Cor primária',
            'secondary_color' => 'Cor secundária',
            'active' => 'Ativo',
        ];
    }
}
