<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentAsset extends Model
{
    protected $fillable = ['business_id','campaign_id','agent_run_id','agent_task_id','created_by','version','platform','content_type','status','title','hook','caption','call_to_action','hashtags','variants','creative_brief','metadata','provider','model','reviewed_at','reviewed_by'];
    protected function casts(): array { return ['hashtags'=>'array','variants'=>'array','creative_brief'=>'array','metadata'=>'array','reviewed_at'=>'datetime']; }
    public function business() { return $this->belongsTo(Business::class); }
    public function campaign() { return $this->belongsTo(Campaign::class); }
    public function run() { return $this->belongsTo(AgentRun::class,'agent_run_id'); }
    public function task() { return $this->belongsTo(AgentTask::class,'agent_task_id'); }

    public function creativeAssets() { return $this->hasMany(\App\Models\CreativeAsset::class); }
    public function socialPublications(){return $this->hasMany(\App\Models\SocialPublication::class);}
}
