<?php




namespace App\Http\Controllers;




use App\Models\Business;
use App\Models\MarketingGoal;
use Illuminate\Http\Request;




class MarketingGoalController extends Controller
{
    public function index()
    {
        $businessIds = auth()->user()
            ->businesses()
            ->pluck('businesses.id');




        $goals = MarketingGoal::query()
            ->with('business')
            ->whereIn('business_id', $businessIds)
            ->latest()
            ->paginate(15);




        return view(
            'marketing-goals.index',
            compact('goals')
        );
    }




    public function create(Request $request)
    {
        $businesses = auth()->user()
            ->businesses()
            ->where('businesses.is_active', true)
            ->get();




        $selectedBusiness = null;




        if ($request->filled('business')) {
            $selectedBusiness = $businesses
                ->firstWhere(
                    'id',
                    (int) $request->business
                );
        }




        return view(
            'marketing-goals.create',
            compact(
                'businesses',
                'selectedBusiness'
            )
        );
    }




    public function store(Request $request)
    {
        $data = $this->validateGoal($request);




$business = auth()->user()
    ->businesses()
    ->where(
        'businesses.id',
        $data['business_id']
    )
    ->firstOrFail();




$goal = $business
    ->marketingGoals()
    ->create([
        ...$data,




        'created_by' =>
            auth()->id(),




        'platforms' =>
            $request->input(
                'platforms',
                []
            ),




        'target_locations' =>
            $this->list(
                $request->target_locations_text
            ),




        'secondary_kpis' =>
            $this->list(
                $request->secondary_kpis_text
            ),
    ]);




return redirect()
    ->route(
        'marketing-goals.show',
        $goal
    )
    ->with(
        'success',
        'Marketing goal created.'
    );
}




    public function show(MarketingGoal $marketingGoal)
    {
        $this->authorize(
            'view',
            $marketingGoal
        );




        $marketingGoal->load([
            'business',
            'campaigns',
        ]);




        return view(
            'marketing-goals.show',
            compact('marketingGoal')
        );
    }




    public function edit(MarketingGoal $marketingGoal)
    {
        $this->authorize(
            'update',
            $marketingGoal
        );




        return view(
            'marketing-goals.edit',
            compact('marketingGoal')
        );
    }




    public function update(
        Request $request,
        MarketingGoal $marketingGoal
    ) {
        $this->authorize(
            'update',
            $marketingGoal
        );




        $data = $this->validateGoal(
            $request,
            false
        );




        unset($data['business_id']);




        $marketingGoal->update([
            ...$data,




            'platforms' =>
                $request->input(
                    'platforms',
                    []
                ),




            'target_locations' =>
                $this->list(
                    $request->target_locations_text
                ),




            'secondary_kpis' =>
                $this->list(
                    $request->secondary_kpis_text
                ),
        ]);




        return redirect()
            ->route(
                'marketing-goals.show',
                $marketingGoal
            )
            ->with(
                'success',
                'Marketing goal updated.'
            );
    }




    public function destroy(
        MarketingGoal $marketingGoal
    ) {
        $this->authorize(
            'delete',
            $marketingGoal
        );




        $marketingGoal->delete();




        return redirect()
            ->route('marketing-goals.index')
            ->with(
                'success',
                'Marketing goal removed.'
            );
    }




    private function validateGoal(
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




            'name' => [
                'required',
                'string',
                'max:200',
            ],




            'goal_type' => [
                'required',
                'in:brand_awareness,lead_generation,sales,website_traffic,engagement,followers,app_installs,event_promotion,product_launch,customer_retention,other',
            ],




            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],




            'status' => [
                'required',
                'in:draft,active,paused,completed,cancelled',
            ],




            'primary_kpi' => [
                'nullable',
                'string',
                'max:100',
            ],




            'target_value' => [
                'nullable',
                'numeric',
                'min:0',
            ],




            'current_value' => [
                'nullable',
                'numeric',
                'min:0',
            ],




            'unit' => [
                'nullable',
                'string',
                'max:50',
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




            'priority' => [
                'required',
                'integer',
                'between:1,5',
            ],




            'constraints' => [
                'nullable',
                'string',
                'max:5000',
            ],




            'success_definition' => [
                'nullable',
                'string',
                'max:5000',
            ],




            'platforms' => [
                'nullable',
                'array',
            ],




            'platforms.*' => [
                'string',
                'max:50',
            ],




            'target_locations_text' => [
                'nullable',
                'string',
            ],




            'secondary_kpis_text' => [
                'nullable',
                'string',
            ],
        ]);
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
