<?php
namespace App\AI\Creative;
interface ImageProvider { /** Returns binary image bytes, mime, model. */ public function generate(string $prompt):array; }
