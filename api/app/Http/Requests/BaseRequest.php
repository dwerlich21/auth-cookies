<?php

namespace App\Http\Requests;

use App\Exceptions\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * Custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [];
    }

    /**
     * Método público para aplicar transformações
     *
     * @param  array  $data  - Dados para transformar
     * @return array - Dados transformados
     */
    public function applyTransformations(array $data): array
    {
        // Temporariamente armazena os dados no request para que possam ser acessados
        $this->merge($data);

        // Chama o método de preparação
        $transformedData = $this->prepareForValidation($data);

        return $transformedData;
    }

    /**
     * Método que deve ser sobrescrito nas classes filhas
     *
     * @param  array  $data  - Dados para transformar
     * @return array - Dados transformados
     */
    protected function prepareForValidation(array $data = []): array
    {
        // Se não foram passados dados, pega do request
        if (empty($data)) {
            $data = $this->all();
        }

        return $data; // Por padrão, retorna os dados sem transformação
    }

    /**
     * Handle a failed validation attempt.
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        $exception = new ValidationException($validator->errors()->toArray());

        throw new HttpResponseException($exception->render());
    }
}
