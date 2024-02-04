<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OpportunityRequest;
use App\Http\Resources\OpportunityResource;
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

    public function toggleStatus(Opportunity $opportunity)
    {
        $opportunity->status = $opportunity->status == 'open' ? 'closed' : 'open';
        $opportunity->save();
        return $this->success(true, 'Opportunity status changed to ' . $opportunity->status);
    }


    public function getOpportunities()
    {
        $most_fit_opportunities = Opportunity::query()
            ->where('user_type_id', auth()->user()->user_type_id)
            ->where('position_id', auth()->user()->profile->position_id)
            ->where('country_id', auth()->user()->profile->country_id)
            ->where('city_id', auth()->user()->profile->city_id)
//            add here a where between for age
//        add here a where gender
            ->pluck('id')->toArray();

        $fit_opportunities = Opportunity::query()->where('user_type_id', auth()->user()->user_type_id)->pluck('id')->toArray();
        $opportunities_id = array_merge($most_fit_opportunities, $fit_opportunities);
        $opportunities = Opportunity::query()->whereIn('id', $opportunities_id)->orderBy('id', 'DESC')->get();
        return $this->success(OpportunityResource::collection($opportunities));

    }


}
