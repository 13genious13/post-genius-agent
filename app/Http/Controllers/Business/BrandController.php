<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function edit(Business $business)
    {
        $this->authorize('update', $business);

        return view(
            'businesses.brand.edit',
            compact('business')
        );
    }

    public function update(
        Request $request,
        Business $business
    ) {
        $this->authorize('update', $business);

        $data = $request->validate([
            'brand_voice' => ['nullable', 'string', 'max:100'],
            'brand_traits_text' => ['nullable', 'string'],
            'words_to_use_text' => ['nullable', 'string'],
            'words_to_avoid_text' => ['nullable', 'string'],
            'target_locations_text' => ['nullable', 'string'],
            'keywords_text' => ['nullable', 'string'],
            'marketing_objectives_text' => ['nullable', 'string'],

            'primary_color' => [
                'nullable',
                'regex:/^#[0-9A-Fa-f]{6}$/',
            ],

            'secondary_color' => [
                'nullable',
                'regex:/^#[0-9A-Fa-f]{6}$/',
            ],

            'accent_color' => [
                'nullable',
                'regex:/^#[0-9A-Fa-f]{6}$/',
            ],

            'logo_url' => ['nullable', 'url', 'max:255'],
        ]);

        $business->update([
            'brand_voice' =>
                $data['brand_voice'] ?? null,

            'brand_traits' =>
                $this->list($data['brand_traits_text'] ?? null),

 'words_to_use' =>
 $this->list($data['words_to_use_text'] ?? null),

 'words_to_avoid' =>
 $this->list($data['words_to_avoid_text'] ?? null),

 'target_locations' =>
 $this->list($data['target_locations_text'] ?? null),

 'keywords' =>
 $this->list($data['keywords_text'] ?? null),

 'marketing_objectives' =>
 $this->list($data['marketing_objectives_text'] ?? null),

 'primary_color' =>
 $data['primary_color'] ?? null,

 'secondary_color' =>
 $data['secondary_color'] ?? null,

 'accent_color' =>
 $data['accent_color'] ?? null,

 'logo_url' =>
 $data['logo_url'] ?? null,
 ]);

 $business->refreshProfileCompletion();

 return back()->with(
     'success',
     'Brand profile updated.'
 );
 }

    private function list(?string $value): array
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
