<?php
namespace App\AI\Creative;
use RuntimeException;
class ImageProviderFactory {
 public function make():ImageProvider {return match(config('creative.provider')) {
  'disabled'=>app(DisabledImageProvider::class),
  'openai'=>app(OpenAIImageProvider::class),
  default=>throw new RuntimeException('Unknown creative image provider configured.')
 };}
}
