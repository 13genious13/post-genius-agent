<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MarketingMemory extends Model {
 protected $fillable=['business_id','campaign_id','memory_key','category','title','observation','confidence','sample_size','evidence_publication_ids','metrics','status','observed_at'];
 protected function casts():array{return ['evidence_publication_ids'=>'array','metrics'=>'array','observed_at'=>'datetime'];}
 public function campaign(){return $this->belongsTo(Campaign::class);}
}
