<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OpportunityRequest;
use App\Models\Opportunity;
use App\Traits\AppResponse;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    use  AppResponse;

    public function create(OpportunityRequest $request)
    {
       $opportunity = Opportunity::query()->create($request->all());
        return $this->success($opportunity->id);
    }
}
