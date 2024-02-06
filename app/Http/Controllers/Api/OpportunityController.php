<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OpportunityRequest;
use App\Http\Resources\OpportunityResource;
use App\Models\Opportunity;
use App\Models\OpportunityApplicant;
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


    public function findOpportunities()
    {

        $applied_opportunities = OpportunityApplicant::query()->where('user_id', auth()->user()->id)
            ->where('user_type_id', auth()->user()->user_type_id)->pluck('opportunity_id')->toArray();

        $opportunities = Opportunity::query()
            ->where('user_id', '!=', auth()->user()->id)
            ->whereNotIn('id', $applied_opportunities)->get();
        return $this->success(OpportunityResource::collection($opportunities));

    }

    public function apply($opportunity_id)
    {
        OpportunityApplicant::query()->create([
            'opportunity_id' => $opportunity_id,
            'user_id' => auth()->user()->id,
            'user_type_id' => auth()->user()->user_type_id,
        ]);
        return $this->success(true);
    }

    public function getUserOpportunities(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|max:255'
        ]);
        $opportunities = [];
//        dd(auth()->user()->id);
        if ($data['type'] == 'applied') {
            $applied_opportunities = OpportunityApplicant::query()->where('user_id', auth()->user()->id)
                ->where('user_type_id', auth()->user()->user_type_id)->pluck('opportunity_id')->toArray();
            $opportunities = Opportunity::query()->whereIn('id', $applied_opportunities)->get();
            return $this->success(OpportunityResource::collection($opportunities));
        } else {
            $opportunities = Opportunity::query()->where('user_id', auth()->user()->id)->get();
            return $this->success(OpportunityResource::collection($opportunities));
        }
    }


}
