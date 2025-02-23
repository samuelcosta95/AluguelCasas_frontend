<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Arr;

class PropertyController extends Controller
{
    private function handleApiResponse($response, $asArray = true)
    {
        try {
            if (!$response->successful()) {
                return [];
            }

            $data = $response->json() ?? [];

            if ($asArray && !is_array($data)) {
                return [];
            }

            return $data;

        } catch (\Exception $e) {
            return [];
        }
    }

    private function checkAuth()
    {
        try {
            if (!Session::has('api_token')) {
                return false;
            }

            $response = Http::withToken(Session::get('api_token'))
                          ->timeout(10)
                          ->get(config('services.api.url') . 'auth/verify/');

            return $response->successful();

        } catch (\Exception $e) {
            Session::flush();
            return false;
        }
    }

    public function index()
    {
        try {
            $response = Http::timeout(10)->get(config('services.api.url') . 'properties/');
            $properties = $this->handleApiResponse($response) ?? [];
            
            return view('home', [
                'properties' => is_array($properties) ? $properties : []
            ]);

        } catch (\Exception $e) {
            return view('home', ['properties' => []])
                ->with('error', 'Erro ao carregar propriedades');
        }
    }

    public function showAddForm()
    {
        if (!$this->checkAuth()) {
            return redirect()->route('login')->with('error', 'Faça login para continuar');
        }
        
        return view('properties.create');
    }

    public function storeProperty(Request $request)
    {
        if (!$this->checkAuth()) {
            return redirect()->route('login');
        }

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:1',
                'bedrooms' => 'required|integer|min:1',
                'location' => 'required|string'
            ]);

            $response = Http::withToken(Session::get('api_token'))
                ->timeout(15)
                ->post(config('services.api.url') . 'properties/', [
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'price_per_night' => $validated['price'],
                    'bedrooms' => $validated['bedrooms'],
                    'location' => $validated['location']
                ]);

            if ($response->successful()) {
                return redirect()->route('my-properties')
                    ->with('success', 'Propriedade cadastrada com sucesso!');
            }

            $error = Arr::get($response->json(), 'detail', 'Erro desconhecido ao cadastrar');
            return back()->withInput()->with('error', $error);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erro de conexão com o servidor')
                ->withInput();
        }
    }

    public function myBookings()
    {
        if (!$this->checkAuth()) {
            return redirect()->route('login');
        }

        try {
            $response = Http::withToken(Session::get('api_token'))
                          ->timeout(10)
                          ->get(config('services.api.url') . 'my-bookings/');

            $bookings = $this->handleApiResponse($response) ?? [];

            return view('bookings.index', [
                'bookings' => is_array($bookings) ? $bookings : []
            ]);

        } catch (\Exception $e) {
            return view('bookings.index', ['bookings' => []])
                ->with('error', 'Erro ao carregar reservas');
        }
    }

    public function myProperties()
    {
        if (!$this->checkAuth()) {
            return redirect()->route('login');
        }

        try {
            $response = Http::withToken(Session::get('api_token'))
                          ->timeout(10)
                          ->get(config('services.api.url') . 'my-properties/');

            $properties = $this->handleApiResponse($response) ?? [];

            return view('properties.index', [
                'properties' => is_array($properties) ? $properties : []
            ]);

        } catch (\Exception $e) {
            return view('properties.index', ['properties' => []])
                ->with('error', 'Erro ao carregar propriedades');
        }
    }

    public function edit($id)
    {
        if (!$this->checkAuth()) {
            return redirect()->route('login');
        }

        try {
            $response = Http::withToken(Session::get('api_token'))
                          ->timeout(10)
                          ->get(config('services.api.url') . "properties/{$id}/");

            $property = $this->handleApiResponse($response);

            if (empty($property) || !is_array($property)) {
                abort(404, 'Propriedade não encontrada');
            }

            return view('properties.edit', [
                'property' => $property
            ]);

        } catch (\Exception $e) {
            abort(404, 'Propriedade não encontrada');
        }
    }

    public function update(Request $request, $id)
    {
        if (!$this->checkAuth()) {
            return redirect()->route('login');
        }

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:1',
                'bedrooms' => 'required|integer|min:1',
                'location' => 'required|string'
            ]);

            $response = Http::withToken(Session::get('api_token'))
                          ->timeout(15)
                          ->put(config('services.api.url') . "properties/{$id}/", [
                              'title' => $validated['title'],
                              'description' => $validated['description'],
                              'price_per_night' => $validated['price'],
                              'bedrooms' => $validated['bedrooms'],
                              'location' => $validated['location']
                          ]);

            if ($response->successful()) {
                return redirect()->route('my-properties')
                    ->with('success', 'Propriedade atualizada com sucesso!');
            }

            $error = Arr::get($response->json(), 'detail', 'Erro desconhecido ao atualizar');
            return back()->withInput()->with('error', $error);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erro de conexão com o servidor')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        if (!$this->checkAuth()) {
            return redirect()->route('login');
        }

        try {
            $response = Http::withToken(Session::get('api_token'))
                          ->timeout(15)
                          ->delete(config('services.api.url') . "properties/{$id}/");

            if ($response->successful()) {
                return redirect()->route('my-properties')
                    ->with('success', 'Propriedade excluída com sucesso!');
            }

            $error = Arr::get($response->json(), 'detail', 'Erro desconhecido ao excluir');
            return back()->with('error', $error);

        } catch (\Exception $e) {
            return back()->with('error', 'Erro de conexão com o servidor');
        }
    }

    public function book(Request $request, $id)
    {
        if (!$this->checkAuth()) return redirect()->route('login');

        $response = Http::withToken(Session::get('api_token'))
                    ->post(config('services.api.url') . 'bookings/', [
                        'property' => (int)$id,
                        'check_in' => $request->check_in,
                        'check_out' => $request->check_out
                    ]);

        if ($response->successful()) {
            return redirect()->route('my-bookings')->with('success', 'Reserva feita!');
        }

        $error = is_array($response->json()) 
            ? ($response->json()['error'] ?? 'Erro desconhecido') 
            : 'Erro na API';

        return back()->with('error', $error);
    }
}