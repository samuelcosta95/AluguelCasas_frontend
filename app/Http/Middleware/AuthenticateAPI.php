<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthenticateAPI
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('api_token')) {
            return redirect()->route('login')->with('error', 'Faça login primeiro');
        }
        
        // Verificação adicional opcional com a API
        $response = Http::withToken(Session::get('api_token'))
                    ->get(config('services.api.url') . 'auth/verify/');
        
        if (!$response->successful()) {
            Session::flush();
            return redirect()->route('login');
        }

        return $next($request);
    }
}