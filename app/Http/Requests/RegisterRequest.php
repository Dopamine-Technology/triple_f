<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    private $commonRoles = [
        'user_type' => 'required|exists:user_types,id|max:255',
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'password' => [
            'required_without_all:facebook_identifier,google_identifier',
            'min:8',
            'max:255',
        ],
        'user_name' => 'sometimes|max:255',
        'image' => 'sometimes',
        'social_image' => 'sometimes',
        'google_identifier' => 'sometimes',
        'facebook_identifier' => 'sometimes',
    ];

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
        $validation_arr = $this->getTypeValidation();
        if (!$this->google_identifier && !$this->facebook_identifier) {
            $validation_arr['email'] = 'required|email|max:255|unique:users,email';
        } else {
            $validation_arr['email'] = 'sometimes|max:255';
        }

        return $validation_arr;
    }

    protected function passedValidation()
    {
        $data = array();
        $data['user']['first_name'] = $this->first_name;
        $data['user']['last_name'] = $this->last_name;
        $data['user']['image'] = $this->image ? $this->file('image')->store('avatars', 'public') : '';
        $data['user']['social_image'] = $this->social_image ?? '';
        $data['user']['name'] = $this->first_name . ' ' . $this->last_name;
        $data['user']['user_name'] = $this->user_name;
        $data['user']['gender'] = $this->gender ?? 'other';
        $data['user']['email'] = $this->email;
        $data['user']['password'] = Hash::make($this->password);
        $data['user']['user_type_id'] = $this->user_type;
        $this->merge($data);
        $this->merge($this->getTypeData());
    }

    public function getTypeValidation()
    {
        switch ($this->user_type) {
            case 1:
                return $this->talentValidationRoles();
                break;
            case 2:
                return $this->couchValidationRoles();
                break;
            case 3:
                return $this->clubValidationRoles();
                break;
            case 4:
                return $this->scoutValidationRoles();
                break;
            default :
                return [];
        }
    }


    public function talentValidationRoles(): array
    {
        $roles = $this->commonRoles;
        $roles['talent_type'] = 'required';
        $roles['parent_position'] = 'required|exists:positions,id';
        $roles['position'] = 'required|exists:positions,id';
        $roles['gender'] = 'required|in:male,female,other';
        $roles['birth_date'] = 'required|date';
        $roles['height'] = 'required|numeric';
        $roles['wight'] = 'required|numeric';
        $roles['country_id'] = 'required';
        $roles['city_id'] = 'required';
        $roles['mobile_number'] = 'required';
        return $roles;
    }

    public function couchValidationRoles(): array
    {
        $roles = $this->commonRoles;
        $roles['talent_type'] = 'required';
        $roles['gender'] = 'required|in:male,female,other';
        $roles['birth_date'] = 'required|date';
        $roles['years_of_experience'] = 'required|numeric';
        $roles['country_id'] = 'required';
        $roles['city_id'] = 'required';
        $roles['mobile_number'] = 'required';
        return $roles;
    }

    public function clubValidationRoles(): array
    {
        $roles = $this->commonRoles;
        $roles['club_name'] = 'required|string|max:255';
        $roles['club_logo'] = 'sometimes';
        $roles['talent_type'] = 'required';
        $roles['country_id'] = 'required';
        $roles['mobile_number'] = 'required';
        $roles['year_founded'] = 'required';
        return $roles;
    }

    public function scoutValidationRoles(): array
    {
        $roles = $this->commonRoles;
        $roles['talent_type'] = 'required';
        $roles['gender'] = 'required|in:male,female,other';
        $roles['birth_date'] = 'required|date';
        $roles['years_of_experience'] = 'required|numeric';
        $roles['country_id'] = 'required';
        $roles['city_id'] = 'required';
        $roles['mobile_number'] = 'required';
        return $roles;
    }


    public function getTypeData()
    {
        switch ($this->user_type) {
            case 1:
                return $this->talentDataHandler();
                break;
            case 2:
                return $this->couachDataHandler();
                break;
            case 3:
                return $this->clubDataHandler();
                break;
            case 4:
                return $this->scoutDataHandler();
                break;
            default :
                return [];
        }
    }

    public function talentDataHandler()
    {
        $data = array();
        $data['profile']['user_type'] = $this->user_type;
        $data['profile']['sport_id'] = $this->talent_type;
        $data['profile']['parent_position_id'] = $this->parent_position;
        $data['profile']['position_id'] = $this->position;
        $data['profile']['gender'] = $this->gender;
        $data['profile']['birth_date'] = $this->birth_date;
        $data['profile']['height'] = $this->height;
        $data['profile']['wight'] = $this->wight;
        $data['profile']['country_id'] = $this->country_id;
        $data['profile']['city_id'] = $this->city_id;
        $data['profile']['mobile_number'] = $this->mobile_number;
        return $data;
    }

    public function couachDataHandler()
    {
        $data = array();
        $data['profile']['user_type'] = $this->user_type;
        $data['profile']['sport_id'] = $this->talent_type;
        $data['profile']['gender'] = $this->gender;
        $data['profile']['birth_date'] = $this->birth_date;
        $data['profile']['country_id'] = $this->country_id;
        $data['profile']['city_id'] = $this->city_id;
        $data['profile']['mobile_number'] = $this->mobile_number;
        $data['profile']['years_of_experience'] = $this->years_of_experience;
        return $data;
    }

    public function clubDataHandler()
    {
        $data = array();
        $data['profile']['user_type'] = $this->user_type;
        $data['profile']['name'] = $this->club_name;
        $data['profile']['logo'] = $this->club_logo ? $this->file('club_logo')->store('clubs', 'public') : '';
        $data['profile']['sport_id'] = $this->talent_type;
        $data['profile']['country_id'] = $this->country_id;
        $data['profile']['mobile_number'] = $this->mobile_number;
        $data['profile']['year_founded'] = $this->year_founded;
        return $data;
    }

    public function scoutDataHandler()
    {
        $data = array();
        $data['profile']['user_type'] = $this->user_type;
        $data['profile']['sport_id'] = $this->talent_type;
        $data['profile']['gender'] = $this->gender;
        $data['profile']['birth_date'] = $this->birth_date;
        $data['profile']['country_id'] = $this->country_id;
        $data['profile']['city_id'] = $this->city_id;
        $data['profile']['mobile_number'] = $this->mobile_number;
        $data['profile']['years_of_experience'] = $this->years_of_experience;
        return $data;
    }


}
