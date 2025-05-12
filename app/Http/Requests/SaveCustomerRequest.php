<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveCustomerRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $customerId = $this->route('customer');

        return [
            'full_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('customers')->ignore($customerId)
            ],
            'cpf' => [
                'required',
                'cpf',
                'numeric',
                Rule::unique('customers')->ignore($customerId)
            ],
            'phone' => 'required|numeric',
            'address.street' => 'required|string|max:255',
            'address.number' => 'sometimes|string|max:16',
            'address.complement' => 'sometimes|string|max:126',
            'address.neighborhood' => 'required|string|max:255',
            'address.state' => 'required|string|max:255',
            'address.city' => 'required|string|max:255',
            'address.zipcode' => 'required|numeric|digits:8',
        ];
    }
}
