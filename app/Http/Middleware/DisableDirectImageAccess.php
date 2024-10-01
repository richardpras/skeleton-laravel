<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DisableDirectImageAccess
{
    public function handle(Request $request, Closure $next)
    {
        $referer = $request->headers->get('referer');
        $host = $request->getHost();
        $refererSet='http://localhost:3000/';
        if ($referer!=$refererSet) {
            return response('Direct access to images is not allowed.'.$referer, 403);
        }

        return $next($request);
    }
}