<?php
namespace App\Http\Controllers;
use App\Models\Business;
use App\Models\SocialAccount;
use App\Social\MetaGraph;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class SocialConnectionController extends Controller {
 private function member(Business $business):void {abort_unless(auth()->user()->businesses()->whereKey($business->id)->exists(),403);}
 public function index(Business $business){$this->member($business);$accounts=$business->socialAccounts()->orderBy('platform')->get();return view('social.accounts',compact('business','accounts'));}
 public function connect(Business $business,MetaGraph $graph){$this->member($business);$this->authorize('update',$business);abort_unless(config('social.app_id') && config('social.app_secret') && config('social.redirect_uri'),503,'Configure Meta app credentials first.');
  $state=Str::random(48);session()->put('meta_oauth',['state'=>hash('sha256',$state),'business_id'=>$business->id,'user_id'=>auth()->id(),'expires_at'=>now()->addMinutes(10)->timestamp]);return redirect()->away($graph->oauthUrl($state));}
 public function callback(Request $request,MetaGraph $graph){$saved=session()->pull('meta_oauth');abort_unless($saved && ($saved['expires_at']??0)>=now()->timestamp && ($saved['user_id']??null)===auth()->id() && hash_equals($saved['state'],hash('sha256',(string)$request->query('state'))),403,'OAuth state invalid or expired.');
  $business=Business::findOrFail($saved['business_id']);$this->member($business);$this->authorize('update',$business);
  if($request->query('error'))return redirect()->route('social.accounts.index',$business)->with('error','Meta authorization was cancelled or denied.');
  $code=$request->query('code');abort_unless(is_string($code)&&strlen($code)>5,422,'Missing OAuth authorization code.');
  $short=$graph->exchangeCode($code);$long=$graph->longLivedToken($short['access_token']);$userToken=$long['access_token'];
  $pages=$graph->call('GET','me/accounts',$userToken,['fields'=>'id,name,access_token,instagram_business_account{id,username}','limit'=>100]);
  $count=0;
  foreach(($pages['data']??[]) as $page){
   if(empty($page['id'])||empty($page['access_token']))continue;
   $base=['connected_by'=>auth()->id(),'status'=>'active','token_expires_at'=>null];
   SocialAccount::updateOrCreate(['business_id'=>$business->id,'platform'=>'facebook','external_id'=>(string)$page['id']],$base+['name'=>$page['name']??'Facebook Page','access_token'=>$page['access_token'],'metadata'=>['page_id'=>(string)$page['id']]]);$count++;
   $ig=$page['instagram_business_account']??null;
   if(!empty($ig['id'])){SocialAccount::updateOrCreate(['business_id'=>$business->id,'platform'=>'instagram','external_id'=>(string)$ig['id']],$base+['name'=>$ig['username']??'Instagram Professional','access_token'=>$page['access_token'],'metadata'=>['page_id'=>(string)$page['id']]]);$count++;}
  }
  return redirect()->route('social.accounts.index',$business)->with('success',$count.' account connection(s) synchronized. Check Meta permissions and token validity.');
 }
 public function disconnect(Business $business,SocialAccount $account){$this->member($business);$this->authorize('update',$business);abort_unless($account->business_id===$business->id,404);$account->update(['status'=>'disconnected','access_token'=>'','token_expires_at'=>now()]);return back()->with('success','Account disconnected locally. Revoke access in Meta settings if needed.');}
}
