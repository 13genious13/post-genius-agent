<?php
namespace App\Jobs;
use App\AI\Creative\BrandGuardian;
use App\AI\Creative\CreativeAgent;
use App\AI\Creative\ImageProviderFactory;
use App\Models\CreativeAsset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;
class GenerateCreativeAsset implements ShouldQueue {
 use Queueable;
 public int $tries=1; public int $timeout=240;
 public function __construct(public int $creativeAssetId){$this->onQueue('creative');}
 public function handle(CreativeAgent $agent,BrandGuardian $guardian,ImageProviderFactory $factory):void {
  $asset=CreativeAsset::with(['contentAsset','business'])->findOrFail($this->creativeAssetId);
  if(!in_array($asset->status,['queued','failed'],true)) return;
  $asset->update(['status'=>'generating','error_message'=>null]);
  try {
   $spec=$agent->design($asset->contentAsset); $prompt=mb_substr($spec['image_prompt'],0,6000);
   $asset->update(['design_spec'=>$spec,'prompt'=>$prompt]);
   $generated=$factory->make()->generate($prompt);
   $bytes=$generated['bytes']??null;
   if(!is_string($bytes)||strlen($bytes)<100||strlen($bytes)>config('creative.max_image_bytes')) throw new RuntimeException('Invalid or oversized generated image.');
   $imageInfo=@getimagesizefromstring($bytes); if(!$imageInfo||!in_array($imageInfo['mime']??'', ['image/png','image/jpeg','image/webp'],true)) throw new RuntimeException('Generated image is not a supported image format.');
   $mime=$imageInfo['mime']; $ext=['image/png'=>'png','image/jpeg'=>'jpg','image/webp'=>'webp'][$mime];
   $disk=config('creative.disk'); $path='creatives/'.$asset->business_id.'/'.$asset->id.'/image.'.$ext;
   if(!Storage::disk($disk)->put($path,$bytes)) throw new RuntimeException('Unable to store generated image.');
   $asset->update(['storage_disk'=>$disk,'storage_path'=>$path,'mime_type'=>$mime,'bytes'=>strlen($bytes),'image_provider'=>config('creative.provider'),'image_model'=>$generated['model']??null,'status'=>'reviewing']);
   $review=$guardian->review($asset->fresh(['contentAsset','business']));
   $asset->update(['brand_review'=>$review,'brand_score'=>$review['score'],'status'=>$review['passed']?'awaiting_approval':'revision_required']);
  } catch(Throwable $e){$asset->update(['status'=>'failed','error_message'=>mb_substr($e->getMessage(),0,1000)]);throw $e;}
 }
 public function failed(?Throwable $e):void {CreativeAsset::whereKey($this->creativeAssetId)->whereNotIn('status',['approved'])->update(['status'=>'failed','error_message'=>mb_substr($e?->getMessage()??'Job failed',0,1000)]);}
}
