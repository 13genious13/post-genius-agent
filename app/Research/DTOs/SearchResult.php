<?php




namespace App\Research\DTOs;




class SearchResult
{
    public function __construct(
        public string $url,
        public ?string $title = null,
        public ?string $content = null,
        public ?string $snippet = null,
        public ?float $score = null,
        public array $metadata = [],
    ) {
    }




    public function domain(): ?string
    {
        $host = parse_url(
            $this->url,
            PHP_URL_HOST
        );




        if (!$host) {
            return null;
        }




        return preg_replace(
            '/^www\./i',
            '',
            strtolower($host)
        );
    }
}
