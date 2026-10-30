<?php
namespace App\Social;
use Illuminate\Support\Facades\Http;
use RuntimeException;
class MetaGraph {
 public function call(string $method,string $path,string $token,array $params=[]):array {
  $url='https://graph.facebook.com/'.config('social.graph_version').'/'.ltrim($path,'/');
  $response=Http::timeout(config('social.http_timeout'))->acceptJson()->withToken($token)->send(strtoupper($method),$url,[strtoupper($method)==='GET'?'query':'form_params'=>$params]);
  if(!$response->successful()){ $body=$response->json();$code=data_get($body,'error.code');$sub=data_get($body,'error.error_subcode');
   throw new RuntimeException('Meta Graph request failed (HTTP '.$response->status().', code '.($code??'?').', subcode '.($sub??'?').').'); }
  return $response->json()??[];
 }
 public function oauthUrl(string $state):string {
  return 'https://www.facebook.com/'.config('social.graph_version').'/dialog/oauth?'.http_build_query([
   'client_id'=>config('social.app_id'),'redirect_uri'=>config('social.redirect_uri'),'state'=>$state,'response_type'=>'code','scope'=>implode(',',config('social.scopes'))]);
 }
 public function exchangeCode(string $code):array {
  $response=Http::timeout(20)->get('https://graph.facebook.com/'.config('social.graph_version').'/oauth/access_token',[
   'client_id'=>config('social.app_id'),'client_secret'=>config('social.app_secret'),'redirect_uri'=>config('social.redirect_uri'),'code'=>$code]);
  if(!$response->successful())throw new RuntimeException('Meta OAuth code exchange failed.');return $response->json();
 }
 public function longLivedToken(string $shortToken):array {
  $response=Http::timeout(20)->get('https://graph.facebook.com/'.config('social.graph_version').'/oauth/access_token',[
   'grant_type'=>'fb_exchange_token','client_id'=>config('social.app_id'),'client_secret'=>config('social.app_secret'),'fb_exchange_token'=>$shortToken]);
  if(!$response->successful())throw new RuntimeException('Meta long-lived token exchange failed.');return $response->json();
 }
}
