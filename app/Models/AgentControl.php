<?php




namespace App\Models;




use Illuminate\Database\Eloquent\Model;




class AgentControl extends Model
{
    protected $fillable = [
        'business_id',
        'autonomy_mode',
        'allow_research',
        'allow_content_generation',
        'allow_creative_generation',
        'allow_scheduling',
        'allow_publishing',
        'require_content_approval',
        'require_creative_approval',
        'require_publish_approval',
        'max_actions_per_run',
        'max_ai_requests_per_run',
        'allowed_platforms',
        'settings',
    ];




    protected function casts(): array
    {
        return [
            'allow_research' => 'boolean',
            'allow_content_generation' => 'boolean',
            'allow_creative_generation' => 'boolean',
            'allow_scheduling' => 'boolean',
            'allow_publishing' => 'boolean',
            'require_content_approval' => 'boolean',
            'require_creative_approval' => 'boolean',
            'require_publish_approval' => 'boolean',
            'allowed_platforms' => 'array',
            'settings' => 'array',
        ];
    }




    public function business()
    {
        return $this->belongsTo(
            Business::class
        );
    }
}
