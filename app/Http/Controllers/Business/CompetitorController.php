<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessCompetitor;
use Illuminate\Http\Request;

class CompetitorController extends Controller
{
    public function index(Business $business)
    {
        $this->authorize('view', $business);

        $competitors = $business
            ->competitors()
            ->latest()
            ->get();

        return view(
            'businesses.competitors.index',
            compact('business', 'competitors')
        );
    }

    public function store(
        Request $request,
        Business $business
    ) {
        $this->authorize('update', $business);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'website' => ['nullable', 'url', 'max:255'],
            'notes' => ['nullable', 'string', 'max:4000'],
        ]);

        $data['is_primary'] =
            $request->boolean('is_primary');

        $business->competitors()
            ->create($data);

        $business->refreshProfileCompletion();

        return back()->with(
            'success',
            'Competitor added.'
        );
    }

    public function destroy(
        Business $business,
        BusinessCompetitor $competitor
    ) {
        $this->authorize('update', $business);

        abort_unless(
            $competitor->business_id === $business->id,
            404
        );

        $competitor->delete();

        $business->refreshProfileCompletion();

        return back()->with(
            'success',
            'Competitor removed.'
        );
    }
}
