<?php

namespace App\Http\Controllers;

use App\Models\AgentControl;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AgentControlController extends Controller
{
    public function edit(
        Business $business
    ) {
        $this->authorize(
            'update',
            $business
        );




        $control =
            AgentControl::firstOrCreate(
                [
                    'business_id' =>
                        $business->id,
                ],
                [
                    'autonomy_mode' =>
                        'assisted',
                ]
            );




        return view(
            'agent-center.controls',
            compact(
                'business',
                'control'
            )
        );
    }




    public function update(
        Request $request,
        Business $business
    ) {
        $this->authorize(
            'update',
            $business
        );




        $data =
            $request->validate([
                'autonomy_mode' => [
                    'required',
                    Rule::in([
                        'manual',
                        'assisted',
                        'autonomous',
                    ]),
                ],




                'max_actions_per_run' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:100',
                ],




                'max_ai_requests_per_run' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:200',
                ],




                'allowed_platforms' => [
                    'nullable',
                    'array',
                ],




                'allowed_platforms.*' => [
                    Rule::in([
                        'facebook',
                        'instagram',
                        'linkedin',
                        'x',
                        'youtube',
                        'blog',
                    ]),
                ],
            ]);




        $booleanFields = [
            'allow_research',
            'allow_content_generation',
            'allow_creative_generation',
            'allow_scheduling',
            'allow_publishing',
            'require_content_approval',
            'require_creative_approval',
            'require_publish_approval',
        ];




        foreach (
            $booleanFields as $field
        ) {
            $data[$field] =
                $request->boolean(
                    $field
                );
        }




        AgentControl::updateOrCreate(
            [
                'business_id' =>
                    $business->id,
            ],
            $data
        );




        return back()->with(
            'success',
            'Agent controls updated.'
        );
    }
}
