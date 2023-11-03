<?php

namespace App\Http\Middleware;

use App\Models\Members;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('member_id')) {
            return redirect('/signin');
        } else {
            $member = Members::where('member_id', session('member_id'))->first();
            if ($member['status'] == 'admin') {
                return $next($request);
            }
            return redirect('/');
        }
    }
}
