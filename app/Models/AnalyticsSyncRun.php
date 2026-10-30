<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AnalyticsSyncRun extends Model {
 protected $fillable=['business_id','social_account_id','initiated_by','status','trigger','posts_scanned','posts_succeeded','posts_failed','error_message','started_at','completed_at'];
 protected function casts():array{return ['started_at'=>'datetime','completed_at'=>'datetime'];}
 public function business(){return $this->belongsTo(Business::class);}public function account(){return $this->belongsTo(SocialAccount::class,'social_account_id');}
}
