<?php

namespace App\Http\Middleware;

use Closure;

class VerifyCors
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $env=config('app.env');
    $domain = isset($_SERVER['HTTP_REFERER'])?parse_url($_SERVER['HTTP_REFERER']):'';
    $host = 'localhost';
    if($env=='production'){
      if (isset($domain['host'])) {
          $domain = $domain['host'];
      }
      if($domain != $host){
        return response()->json(['error' => 'Unauthorized'], 401);
      }
    }
    return $next($request)
        ->header('Access-Control-Allow-Origin', $host)
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');
  }
}