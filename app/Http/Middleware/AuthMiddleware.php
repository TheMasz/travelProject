<?php

namespace App\Http\Middleware;

use App\Models\Members;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('member_id')) {
            $member = Members::where('member_id', session('member_id'))->first();
            if ($member['status'] == 'admin') {
                return redirect('/admin/dashboard');
            }
            return redirect('/');
        }
        return $next($request);
    }
}
