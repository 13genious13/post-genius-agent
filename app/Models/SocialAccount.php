<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SocialAccount extends Model {
 protected $fillable=['business_id','connected_by','platform','external_id','name','access_token','token_expires_at','status','metadata'];
 protected $hidden=['access_token'];
 protected function casts():array{return ['access_token'=>'encrypted','token_expires_at'=>'datetime','metadata'=>'array'];}
 public function business(){return $this->belongsTo(Business::class);}
 public function publications(){return $this->hasMany(SocialPublication::class);}
 public function isUsable():bool{return $this->status==='active' && (!$this->token_expires_at || $this->token_expires_at->isFuture());}
}
