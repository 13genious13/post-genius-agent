<?php
namespace App\AI\Creative;
use Illuminate\Support\Facades\Http;
use RuntimeException;
class OpenAIImageProvider implements ImageProvider {
 public function generate(string $prompt):array {
  $key=config('creative.openai_api_key'); if(!$key) throw new RuntimeException('Creative image provider API key is missing.');
  $model=config('creative.openai_image_model');
  $response=Http::withToken($key)->acceptJson()->timeout(180)->post('https://api.openai.com/v1/images/generations',[
   'model'=>$model,'prompt'=>$prompt,'size'=>'1024x1024','n'=>1
  ]);
  if(!$response->successful()) throw new RuntimeException('Image provider returned HTTP '.$response->status());
  $encoded=$response->json('data.0.b64_json');
  if(!is_string($encoded)||$encoded==='') throw new RuntimeException('Image provider returned no base64 image.');
  $bytes=base64_decode($encoded,true); if($bytes===false) throw new RuntimeException('Invalid image encoding.');
  return ['bytes'=>$bytes,'mime'=>'image/png','model'=>$model];
 }
}
