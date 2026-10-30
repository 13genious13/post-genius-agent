<?php




namespace App\Models;




use Illuminate\Database\Eloquent\Model;




class StrategyEvidence extends Model
{
    protected $table =
        'strategy_evidence';




    protected $fillable = [
        'campaign_strategy_id',
        'research_source_id',
        'decision_key',
        'decision_summary',
        'evidence_role',
    ];




    public function strategy()
    {
        return $this->belongsTo(
            CampaignStrategy::class,
            'campaign_strategy_id'
        );
    }




    public function source()
    {
        return $this->belongsTo(
            ResearchSource::class,
            'research_source_id'
        );
    }
}
