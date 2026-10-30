<?php

namespace App\Http\Controllers;

use App\Models\ContentAsset;
use Illuminate\Http\Request;

class ContentLibraryController extends Controller
{
    public function index()
    {
        $ids = auth()->user()->businesses()->pluck('businesses.id');
        $assets = ContentAsset::with('campaign','business')->whereIn('business_id',$ids)->latest()->paginate(20);
        return view('content-library.index',compact('assets'));
    }
    public function show(ContentAsset $contentAsset)
    {
        $this->authorize('view',$contentAsset->campaign);
        return view('content-library.show',['asset'=>$contentAsset]);
    }
    public function approve(ContentAsset $contentAsset)
    {
        $this->authorize('update',$contentAsset->campaign);
        abort_unless($contentAsset->status==='draft',422);
        $contentAsset->update(['status'=>'approved','reviewed_at'=>now(),'reviewed_by'=>auth()->id()]);
        return back()->with('success','Content approved for future scheduling. Not published.');
    }
    public function reject(Request $request, ContentAsset $contentAsset)
    {
        $this->authorize('update',$contentAsset->campaign);
        abort_unless($contentAsset->status==='draft',422);
        $data=$request->validate(['reason'=>['required','string','max:1000']]);
        $contentAsset->update(['status'=>'rejected','reviewed_at'=>now(),'reviewed_by'=>auth()->id(),'metadata'=>array_merge($contentAsset->metadata??[],['rejection_reason'=>$data['reason']])]);
        return back()->with('success','Draft rejected.');
    }
}
