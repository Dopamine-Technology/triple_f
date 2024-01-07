<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatusRequest extends FormRequest
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
            'title' => 'sometimes',
            'description' => 'sometimes',
            'challenge_id' => 'sometimes',
            'video' => 'sometimes',
            'image' => 'sometimes',
        ];
    }

    protected function passedValidation()
    {
        $data = array();
        $data['status']['user_id'] = auth()->user()->id;
        $data['status']['title'] = $this->title ?? '';
        $data['status']['description'] = $this->description ?? '';
        $data['status']['challenge_id'] = $this->challenge_id ?? 0;
        $data['status']['video'] = $this->video ? $this->file('video')->store('statuses', 'public') : '';
        $data['status']['image'] = $this->image ? $this->file('image')->store('statuses', 'public') : '';
        $this->merge($data);
    }
}
