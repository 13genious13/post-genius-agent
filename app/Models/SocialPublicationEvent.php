<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SocialPublicationEvent extends Model {
 protected $fillable=['social_publication_id','actor_id','event','from_status','to_status','message','data'];
 protected function casts():array{return ['data'=>'array'];}
 public function publication(){return $this->belongsTo(SocialPublication::class,'social_publication_id');}
}
