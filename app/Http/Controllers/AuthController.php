<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $response = Http::post(config('services.api.url') . 'auth/login/', [
            'username' => $request->username,
            'password' => $request->password
        ]);

        if ($response->successful()) {
            $data = $response->json();
            Session::put([
                'api_token' => $data['access'],
                'user_id' => $data['user']['id'],
                'username' => $data['user']['username']
            ]);
            
            return redirect()->intended(route('home'));
        }

        return back()->withErrors(['message' => 'Credenciais inválidas']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $response = Http::post(config('services.api.url') . 'auth/register/', [
            'username' => $request->username,
            'password' => $request->password
        ]);

        if ($response->created()) {
            return redirect()->route('login')->with('success', 'Cadastro realizado! Faça login');
        }

        return back()->withErrors(['message' => 'Erro no cadastro']);
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('home');
    }
}