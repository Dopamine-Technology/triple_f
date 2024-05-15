<?php

namespace App\Http\Resources;

use App\Models\Follow;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name ?? '',
            'last_name' => $this->last_name ?? '',
            'name' => $this->first_name ?? '' . ' ' . $this->last_name ?? '',
//            'image' => $this->image ? asset('storage/' . $this->image) : '',
            'image' => $this->image ? 'https://acceptance-test.eu-central-1.linodeobjects.com/' . $this->image : '',
            'social_image' => $this->social_image ?? '',
            'user_name' => $this->user_name ?? '',
            'email' => $this->email,
            'is_blocked' => $this->is_blocked,
            'is_baned' => $this->baned_to ? true : false,
            'is_followed' => auth()->user()?->id ? in_array($this->id, Follow::query()->where('user_id', auth()->user()->id)->pluck('followed_id')->toArray()) : false,
            'following_count' => Follow::query()->where('user_id', $this->id)->count(),
            'followers_count' => Follow::query()->where('followed_id', $this->id)->count(),
            'is_email_verified' => $this->email_verified_at ? true : false,
            'profile_progress' => $this->progress,
            'profile' => [
                'club_name' => $this->profile->name ?? '',
                'club_logo' => $this->profile?->logo ? asset('storage/' . $this->profile->logo) : '',
                'type_id' => $this->user_type_id,
                'type_name' => $this->profile_type->getTranslation('name', LANGUAGE),
                'mobile_number' => $this->profile->mobile_number,
                'sport' => [
                    'id' => $this->profile->sport->id,
                    'name' => $this->profile->sport->getTranslation('name', LANGUAGE),
                ],
                'gender' => $this->profile->gender ?? '',
                'height' => $this->profile->height ?? '',
                'wight' => $this->profile->wight ?? '',
                'preferred_foot' => $this->profile->preferred_foot ?? '',
                'year_founded' => $this->profile->year_founded ?? '',
                'birth_date' => $this->profile->birth_date ? Carbon::make($this->profile->birth_date)->format('Y-m-d') : '',
                'residence_place' => $this->profile->residence_place ?? '',
                'country' => $this->profile->country ? [
                    'id' => $this->profile->country->id,
                    'name' => $this->profile->country->getTranslation('name', LANGUAGE),
                    'image'=> asset($this->image),
                ] : null,
                'city' => $this->profile->city ? [
                    'id' => $this->profile->city->id,
                    'name' => $this->profile->city->getTranslation('name', LANGUAGE),
                ] : null,
                'years_of_experience' => $this->profile->years_of_experience ?? '',
                'parent_position' => $this->profile->parent_position ? [
                    'id' => $this->profile->parent_position->id,
                    'name' => $this->profile->parent_position->getTranslation('name', LANGUAGE),
                ] : null,
                'position' => $this->profile->positions ? PostionsResource::collection(Position::query()->whereIn('id', $this->profile->positions)->get()) : null,
                "certificates" => $this->profile->certificates ? CertificateResource::collection($this->profile->certificates) : []
            ],
        ];
    }
}
