<?php

namespace App\Http\Controllers\Api\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Club;
use App\Models\Coach;
use App\Models\Talent;
use App\Models\User;
use App\Traits\AppResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use AppResponse;

    public function emailRegister(RegisterRequest $request)
    {
        $userData = $request->user;
        $profileData = $request->profile;
        $newUser = User::query()->create($userData);
        $profileData['user_id'] = $newUser->id;
        $this->profileCreationHandler($profileData);
        return $this->success([
            'user' => $newUser,
            'token' => $newUser->createToken('apptoken')->plainTextToken,
        ], __('User successfully created'));
    }

    public function profileCreationHandler($profileData)
    {
        $type = $profileData['user_type'];
        unset($profileData['user_type']);
        switch ($type) {
            case 1 :
                return Talent::query()->create($profileData);
            case 2 :
                return Coach::query()->create($profileData);
                case 3 :
                return Club::query()->create($profileData);
        }

    }

}
