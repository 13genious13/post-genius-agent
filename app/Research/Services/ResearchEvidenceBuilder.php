<?php




namespace App\Research\Services;




use App\Models\ResearchRun;




class ResearchEvidenceBuilder
{
    public function build(
        ResearchRun $run
    ): array {
        $run->load([
            'queries',
            'sources.researchQuery',
        ]);




        $maxContent =
            config(
                'research.max_source_content',
                4000
            );




        return $run
            ->sources
            ->map(
                function ($source) use (
                    $maxContent
                ) {
                    return [
                        'source_id' =>
                            $source->id,




                        'title' =>
                            $source->title,




                        'url' =>
                            $source->url,




                        'domain' =>
                            $source->domain,




                        'query' =>
                            $source
                                ->researchQuery
                                ?->query,




                        'content' =>
                            mb_substr(
                                $source->content
                                    ?: $source->snippet
                                    ?: '',
                                0,
                                $maxContent
                            ),




                        'search_score' =>
                            $source
                                ->search_score,
                    ];
                }
            )
            ->values()
            ->all();
    }
}
