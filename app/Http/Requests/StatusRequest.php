<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

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
        $data['status']['video'] = $this->video ? Storage::disk('linode')->put('statuses/videos', $this->video) : '';
        $data['status']['image'] = $this->image ? Storage::disk('linode')->put('statuses/images', $this->image) : '';
        $this->merge($data);
    }
}
