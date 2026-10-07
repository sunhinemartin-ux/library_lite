<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Exception;

class UserController extends Controller
{
   public function index()
    {
        $users = User::all();

        $apiKey = env('WEATHERSTACK_API_KEY');
        $city = env('WEATHERSTACK_DEFAULT_CITY', 'Arakan');

        $weatherData = null;
        $weatherError = null;

        try {
           $response = Http::timeout(10)->get('http://api.weatherstack.com/current', [
                'access_key' => $apiKey,
                'query' => $city
            ]);

            if ($response->successful() && !isset($response->json()['error'])) {
                $data = $response->json();

                $weatherData = [
                    'city' => $data['location']['name'] ?? $city,
                    'temperature' => $data['current']['temperature'] ?? 0,
                    'description' => $data['current']['weather_descriptions'][0] ?? 'N/A',
                    'humidity' => $data['current']['humidity'] ?? 0,
                    'wind_speed' => $data['current']['wind_speed'] ?? 0,
                    'icon' => $data['current']['weather_icons'][0] ?? null
                ];
            } else {
                $weatherError = 'Unable to fetch weather data at the moment.';
            }
        } catch (Exception $e) {
            $weatherError = 'Weather service is currently unavailable.';
        }

        return view('admin-dashboard', compact('users', 'weatherData', 'weatherError'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,librarian,student',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'New user created successfully!');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,librarian,student',
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'User role updated successfully!');
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully!');
    }
}