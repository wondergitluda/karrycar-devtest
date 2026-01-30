<?php

namespace App\Http\Requests;

use App\Enums\ReferentScope;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShipmentReferentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'mode' => ['required', Rule::in(['existing', 'create'])],
            'scope' => ['required', Rule::enum(ReferentScope::class)],
        ];

        if ($this->input('mode') === 'existing') {
            $rules['referent_id'] = ['required', 'integer', 'exists:referents,id'];
        } else {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['last_name'] = ['required', 'string', 'max:255'];
            $rules['email'] = ['required', 'email', 'max:255'];
            $rules['phone'] = ['nullable', 'string', 'max:20'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'scope.enum' => 'Invalid scope. Must be "start" or "end".',
            'referent_id.exists' => 'The selected referent does not exist.',
        ];
    }
}
