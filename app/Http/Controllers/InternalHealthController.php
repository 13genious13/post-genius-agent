<?php
namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
class InternalHealthController extends Controller {
 public function __invoke(Request $request):JsonResponse {
  $expected=(string)config('production_readiness.health_token','');
  if($expected==='' || !hash_equals($expected,(string)$request->bearerToken()))abort(404);
  try{DB::select('SELECT 1');return response()->json(['status'=>'ok','database'=>'ok']);}
  catch(Throwable $e){return response()->json(['status'=>'degraded','database'=>'unavailable'],503);}
 }
}
