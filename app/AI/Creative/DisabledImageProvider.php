<?php
namespace App\AI\Creative;
use RuntimeException;
class DisabledImageProvider implements ImageProvider {
 public function generate(string $prompt):array {throw new RuntimeException('Image generation is disabled. Configure CREATIVE_IMAGE_PROVIDER=openai and CREATIVE_OPENAI_API_KEY, or bind another image provider.');}
}
