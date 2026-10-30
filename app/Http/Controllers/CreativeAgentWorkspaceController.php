<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\ContentAsset;
use App\Models\CreativeAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreativeAgentWorkspaceController extends Controller
{
    public function index(Request $request)
    {
        $businessIds = $request->user()->businesses()->pluck('businesses.id');
        $businesses = Business::query()->whereIn('id', $businessIds)->orderBy('name')->get(['id','name']);
        $selectedBusiness = $request->integer('business_id') ?: null;
        abort_if($selectedBusiness && !$businessIds->contains($selectedBusiness), 403);

        $contents = ContentAsset::query()->with(['business','campaign'])
            ->whereIn('business_id', $businessIds)
            ->when($selectedBusiness, fn ($q) => $q->where('business_id', $selectedBusiness))
            ->whereIn('status', ['draft','approved'])
            ->latest()->limit(100)->get();

        $assetsQuery = CreativeAsset::query()->with(['business','contentAsset'])
            ->whereIn('business_id', $businessIds)
            ->when($selectedBusiness, fn ($q) => $q->where('business_id', $selectedBusiness));
        $stats = [
            'total' => (clone $assetsQuery)->count(),
            'in_progress' => (clone $assetsQuery)->whereIn('status', ['queued','generating','reviewing'])->count(),
            'review' => (clone $assetsQuery)->whereIn('status', ['awaiting_approval','revision_required'])->count(),
            'approved' => (clone $assetsQuery)->where('status','approved')->count(),
        ];
        $assets = $assetsQuery->latest()->paginate(12)->withQueryString();
        return view('creative-agent.index', compact('businesses','selectedBusiness','contents','assets','stats'));
    }

    public function generate(Request $request)
    {
        $data = $request->validate(['content_asset_id' => ['required','integer','exists:content_assets,id']]);
        $content = ContentAsset::query()->with(['campaign','business.agentControl'])->findOrFail($data['content_asset_id']);
        abort_unless($request->user()->businesses()->whereKey($content->business_id)->exists(),403);
        abort_unless($content->campaign,422,'Content must belong to a campaign.');
        $this->authorize('update', $content->campaign);
        abort_unless(in_array($content->status,['draft','approved'],true),422,'Content is not eligible.');
        abort_unless($content->business->agentControl?->allow_creative_generation,403,'Creative generation is disabled for this business.');

        // Delegate all versioning, locking, status checks and queue dispatch to the existing controller.
        return app(CreativeLibraryController::class)->generate($content);
    }
}
