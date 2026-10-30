<?php




namespace App\Http\Controllers;




use App\Models\Business;
use App\Models\Campaign;
use App\Models\MarketingGoal;
use Illuminate\Http\Request;




class CampaignController extends Controller
{
    public function index()
    {
        $businessIds = auth()->user()
            ->businesses()
            ->pluck('businesses.id');




        $campaigns = Campaign::query()
            ->with([
                'business',
                'marketingGoal',
                'latestBrief',
            ])
            ->whereIn(
                'business_id',
                $businessIds
            )
            ->latest()
            ->paginate(15);




        return view(
            'campaigns.index',
            compact('campaigns')
        );
    }




    public function create(Request $request)
    {
        $businesses = auth()->user()
            ->businesses()
            ->where(
                'businesses.is_active',
                true
            )
            ->get();




        $selectedBusiness = null;
        $selectedGoal = null;




        if ($request->filled('business')) {
            $selectedBusiness =
                $businesses->firstWhere(
                    'id',
                    (int) $request->business
                );
        }




        if ($request->filled('goal')) {
            $selectedGoal =
                MarketingGoal::find(
                    $request->goal
                );




            if (
                !$selectedGoal
                || !$businesses->contains(
                    'id',
                    $selectedGoal->business_id
                )
            ) {
                $selectedGoal = null;
            }




            if ($selectedGoal) {
                $selectedBusiness =
                    $businesses->firstWhere(
                        'id',
                        $selectedGoal->business_id
                    );
            }
        }




        $goals = MarketingGoal::query()
            ->whereIn(
                'business_id',
                $businesses->pluck('id')
            )
            ->whereIn(
                'status',
                ['draft', 'active']
            )
            ->orderBy('name')
            ->get();




        $audiences = $selectedBusiness
            ? $selectedBusiness
                ->audiences()
                ->get()
            : collect();




        return view(
            'campaigns.create',
            compact(
                'businesses',
                'goals',
                'audiences',
                'selectedBusiness',
                'selectedGoal'
            )
        );
    }




    public function store(Request $request)
    {
        $data = $this->validateCampaign(
            $request
        );




        $business = auth()->user()
            ->businesses()
            ->where(
                'businesses.id',
                $data['business_id']
            )
            ->firstOrFail();




        $this->validateGoalOwnership(
            $business,
            $data['marketing_goal_id']
            ?? null
        );




        $audienceIds =
            $this->validatedAudienceIds(
                $business,
                $request->input(
                    'target_audience_ids',
                    []
                )
            );




        $campaign =
            $business->campaigns()->create([
                ...$data,




                'created_by' =>
                    auth()->id(),




                'target_audience_ids' =>
                    $audienceIds,




                'platforms' =>
                    $request->input(
                        'platforms',
                        []
                    ),




                'content_formats' =>
                    $request->input(
                        'content_formats',
                        []
                    ),




                'target_locations' =>
                    $this->list(
                        $request->target_locations_text
                    ),




                'primary_kpis' =>
                    $this->list(
                        $request->primary_kpis_text
                    ),
            ]);




        return redirect()
            ->route(
                'campaigns.show',
                $campaign
            )
            ->with(
                'success',
                'Campaign created successfully.'
            );
    }




    public function show(Campaign $campaign)
    {
        $this->authorize(
            'view',
            $campaign
        );




        $campaign->load([
            'business.latestIntelligence',
            'marketingGoal',
            'latestBrief',
            'latestResearch',
            'latestStrategy',
        ]);




        return view(
            'campaigns.show',
            compact('campaign')
        );
    }




    public function edit(Campaign $campaign)
    {
        $this->authorize(
            'update',
            $campaign
        );




        $business =
            $campaign->business;




        $goals = $business
            ->marketingGoals()
            ->orderBy('name')
            ->get();




        $audiences = $business
            ->audiences()
            ->get();




        return view(
            'campaigns.edit',
            compact(
                'campaign',
                'business',
                'goals',
                'audiences'
            )
        );
    }




    public function update(
        Request $request,
        Campaign $campaign
    ) {
        $this->authorize(
            'update',
            $campaign
        );




        $data = $this->validateCampaign(
            $request,
            false
        );




        unset($data['business_id']);




        $this->validateGoalOwnership(
            $campaign->business,
            $data['marketing_goal_id']
            ?? null
        );




        $audienceIds =
            $this->validatedAudienceIds(
                $campaign->business,
                $request->input(
                    'target_audience_ids',
                    []
                )
            );




        $campaign->update([
            ...$data,




            'target_audience_ids' =>
                $audienceIds,




            'platforms' =>
                $request->input(
                    'platforms',
                    []
                ),




            'content_formats' =>
                $request->input(
                    'content_formats',
                    []
                ),




            'target_locations' =>
                $this->list(
                    $request->target_locations_text
                ),




            'primary_kpis' =>
                $this->list(
                    $request->primary_kpis_text
                ),
        ]);




        return redirect()
            ->route(
                'campaigns.show',
                $campaign
            )
            ->with(
                'success',
                'Campaign updated.'
            );
    }




    public function destroy(Campaign $campaign)
    {
        $this->authorize(
            'delete',
            $campaign
        );




        $campaign->delete();




        return redirect()
            ->route('campaigns.index')
            ->with(
                'success',
                'Campaign archived.'
            );
    }




    private function validateCampaign(
        Request $request,
        bool $requireBusiness = true
    ): array {
        return $request->validate([
            'business_id' => [
                $requireBusiness
                    ? 'required'
                    : 'nullable',
                'integer',
                'exists:businesses,id',
            ],




            'marketing_goal_id' => [
                'nullable',
                'integer',
                'exists:marketing_goals,id',
            ],




            'name' => [
                'required',
                'string',
                'max:200',
            ],




            'campaign_type' => [
                'required',
                'in:integrated,social_media,content,lead_generation,paid_media,launch,awareness,engagement,retention',
            ],




            'status' => [
                'required',
                'in:draft,planning,ready,active,paused,completed,cancelled',
            ],




            'objective' => [
                'required',
                'string',
                'max:5000',
            ],




            'offer' => [
                'nullable',
                'string',
                'max:3000',
            ],




            'call_to_action' => [
                'nullable',
                'string',
                'max:1000',
            ],




            'budget' => [
                'nullable',
                'numeric',
                'min:0',
            ],




            'currency' => [
                'required',
                'string',
                'max:10',
            ],




            'start_date' => [
                'nullable',
                'date',
            ],




            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],




            'brand_constraints' => [
                'nullable',
                'string',
                'max:5000',
            ],




            'campaign_constraints' => [
                'nullable',
                'string',
                'max:5000',
            ],




            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],




            'platforms' => [
                'nullable',
                'array',
            ],




            'content_formats' => [
                'nullable',
                'array',
            ],




            'target_audience_ids' => [
                'nullable',
                'array',
            ],




            'target_audience_ids.*' => [
                'integer',
            ],




            'target_locations_text' => [
                'nullable',
                'string',
            ],




            'primary_kpis_text' => [
                'nullable',
                'string',
            ],
        ]);
    }




    private function validateGoalOwnership(
        Business $business,
        ?int $goalId
    ): void {
        if (!$goalId) {
            return;
        }




        abort_unless(
            $business
                ->marketingGoals()
                ->whereKey($goalId)
                ->exists(),
            422,
            'Selected goal does not belong to this business.'
        );
    }




    private function validatedAudienceIds(
        Business $business,
        array $ids
    ): array {
        if (!$ids) {
            return [];
        }




        return $business
            ->audiences()
            ->whereIn('id', $ids)
            ->pluck('id')
            ->map(
                fn ($id) => (int) $id
            )
            ->values()
            ->all();
    }




    private function list(
        ?string $value
    ): array {
        if (!$value) {
            return [];
        }




        return collect(
            preg_split(
                '/[\r\n,]+/',
                $value
            )
        )
            ->map(
                fn ($item) =>
                trim($item)
            )
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
