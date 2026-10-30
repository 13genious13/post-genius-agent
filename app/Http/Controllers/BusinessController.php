<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    public function index()
    {
        $businesses = auth()->user()
            ->businesses()
            ->latest('businesses.created_at')
            ->paginate(12);

        return view(
            'businesses.index',
            compact('businesses')
        );
    }

    public function create()
    {
        return view('businesses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'industry' => ['required', 'string', 'max:150'],
            'business_type' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'url', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'country' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'timezone' => ['nullable', 'timezone'],
        ]);

        $business=DB::transaction(function()use($data) {

            $baseSlug=Str::slug($data['name']);

            $slug = $baseSlug;
            $counter = 1;

            while (Business::withTrashed()
                ->where('slug', $slug)
                ->exists()) {

                $slug = $baseSlug . '-' . $counter++;
            }

            $business = Business::create([
                ...$data,
                'slug' => $slug,
                'timezone' => $data['timezone'] ?? 'Asia/Kolkata',
            ]);

            $business->users()->attach(
                auth()->id(),
                ['role' => 'owner']
            );

            $business->refreshProfileCompletion();

            return $business;
        });

        return redirect()
            ->route('businesses.show', $business)
            ->with(
                'success',
                'Business workspace created successfully.'
            );
    }

    public function show(Business $business)
    {
        $this->authorize('view', $business);

        $business->load([
            'productsServices',
            'audiences',
            'competitors',
            'channels',
            'latestIntelligence',
            'marketingGoals',
            'campaigns',
        ]);

        return view(
            'businesses.show',
            compact('business')
        );
    }

    public function edit(Business $business)
    {
        $this->authorize('update', $business);

        return view(
            'businesses.edit',
            compact('business')
        );
    }

    public function update(
        Request $request,
        Business $business
    ) {
        $this->authorize('update', $business);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'industry' => ['required', 'string', 'max:150'],
            'business_type' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'url', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'mission' => ['nullable', 'string', 'max:3000'],
            'vision' => ['nullable', 'string', 'max:3000'],
            'unique_value_proposition' => ['nullable', 'string', 'max:3000'],
            'brand_story' => ['nullable', 'string', 'max:5000'],
            'country' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'timezone' => ['nullable', 'timezone'],
            'primary_language' => ['nullable', 'string', 'max:10'],
        ]);

        $business->update($data);

 $business->refreshProfileCompletion();

 return redirect()
     ->route('businesses.show', $business)
     ->with(
         'success',
         'Business profile updated.'
     );
 }

    public function destroy(Business $business)
    {
        $this->authorize('delete', $business);

        $business->delete();

        return redirect()
            ->route('businesses.index')
            ->with(
                'success',
                'Business archived successfully.'
            );
    }
}
