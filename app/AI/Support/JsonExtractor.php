<?php

namespace App\AI\Support;

class JsonExtractor
{
    public static function extract(
        string $content
    ): ?array {

        $content = trim($content);

        $direct = json_decode(
            $content,
            true
        );

        if (
            json_last_error()
            === JSON_ERROR_NONE
        ) {
            return $direct;
        }

        $content = preg_replace(
            '/^```(?:json)?\s*|\s*```$/i',
            '',
            $content
        );

        $decoded = json_decode(
            trim($content),
            true
        );

        return json_last_error()
        === JSON_ERROR_NONE
            ? $decoded
            : null;
    }
}
