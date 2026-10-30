<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CreativeApproval extends Model {
 protected $fillable=['creative_asset_id','actor_id','decision','notes'];
 public function creativeAsset(){return $this->belongsTo(CreativeAsset::class);}
 public function actor(){return $this->belongsTo(User::class,'actor_id');}
}
