<?php




namespace App\Models;




use Illuminate\Database\Eloquent\Model;




class ResearchSource extends Model
{
    protected $fillable = [
        'research_run_id',
        'research_query_id',
        'title',
        'url',
        'domain',
        'content',
        'snippet',
        'search_score',
        'source_type',
        'credibility',
        'used_in_analysis',
        'metadata',
    ];




    protected function casts(): array
    {
        return [
            'search_score' => 'decimal:6',
            'used_in_analysis' => 'boolean',
            'metadata' => 'array',
        ];
    }




    public function researchRun()
    {
        return $this->belongsTo(
            ResearchRun::class
        );
    }




    public function researchQuery()
    {
        return $this->belongsTo(
            ResearchQuery::class
        );
    }
}
