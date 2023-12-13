<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Traits\AppResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use AppResponse;

    public function getUserPermission()
    {
        $permissions = auth()->user()->profile_type->permissions;
        return $this->success($permissions);
    }
}
