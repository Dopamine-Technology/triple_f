<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpportunityRequest extends FormRequest
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

            'requirements' => 'required',
            'additional_info' => 'sometimes',
            'position_id' => 'sometimes',
            'targeted_type' => 'sometimes',
            'from_age' => 'sometimes',
            'to_age' => 'sometimes',
            'languages' => 'sometimes',
            'from_height' => 'sometimes',
            'to_height' => 'sometimes',
            'from_weight' => 'sometimes',
            'to_weight' => 'sometimes',
            'gender' => 'sometimes',
            'foot' => 'sometimes',
            'country_id' => 'sometimes',
            'city_id' => 'sometimes',
        ];
    }

    protected function passedValidation()
    {
        $data = array();
        $data['user_id'] = auth()->user()->id;
        $data['user_type_id'] = auth()->user()->user_type_id;
        $this->merge($data);
    }
}
