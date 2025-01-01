<?php

namespace App\Http\Middleware;

use App\Models\Addon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAddonUploader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $addon = Addon::find($request->route('id'));

        if ($addon && ! in_array($addon->blid->id, $request->user()->blids->pluck('id')->toArray())) {
            abort(403);
        }

        return $next($request);
    }
}
