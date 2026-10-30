<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessAudience;
use Illuminate\Http\Request;

class AudienceController extends Controller
{
    public function index(Business $business)
    {
        $this->authorize('view', $business);

        $audiences = $business
            ->audiences()
            ->latest()
            ->get();

        return view(
            'businesses.audiences.index',
            compact('business', 'audiences')
        );
    }

    public function store(
        Request $request,
        Business $business
    ) {
        $this->authorize('update', $business);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', 'string', 'max:50'],
            'age_range' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:4000'],

            'locations_text' => ['nullable', 'string'],
            'interests_text' => ['nullable', 'string'],
            'pain_points_text' => ['nullable', 'string'],
            'goals_text' => ['nullable', 'string'],
            'objections_text' => ['nullable', 'string'],
            'buying_triggers_text' => ['nullable', 'string'],
            'preferred_channels_text' => ['nullable', 'string'],
        ]);

        $business->audiences()->create([
            'name' => $data['name'],
            'type' => $data['type'],
            'age_range' => $data['age_range'] ?? null,
            'gender' => $data['gender'] ?? null,
            'description' => $data['description'] ?? null,

            'locations' => $this->lines(
                $data['locations_text'] ?? null
            ),

            'interests' => $this->lines(
                $data['interests_text'] ?? null
            ),

            'pain_points' => $this->lines(
                $data['pain_points_text'] ?? null
            ),

            'goals' => $this->lines(
                $data['goals_text'] ?? null
            ),

            'objections' => $this->lines(
                $data['objections_text'] ?? null
            ),

            'buying_triggers' => $this->lines(
                $data['buying_triggers_text'] ?? null
            ),

            'preferred_channels' => $this->lines(
                $data['preferred_channels_text'] ?? null
            ),

            'is_primary' =>
                $request->boolean('is_primary'),
        ]);

        $business->refreshProfileCompletion();

        return back()->with(
            'success',
            'Audience persona added.'
        );
    }

    public function destroy(
        Business $business,
        BusinessAudience $audience
    ) {
        $this->authorize('update', $business);

        abort_unless(
            $audience->business_id === $business->id,
            404
        );

        $audience->delete();

        $business->refreshProfileCompletion();

        return back()->with(
            'success',
            'Audience removed.'
        );
    }

    private function lines(?string $value): array
    {
        if (!$value) {
            return [];
        }

        return collect(
            preg_split('/[\r\n,]+/', $value)
        )
            ->map(fn ($item)=>trim($item))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
