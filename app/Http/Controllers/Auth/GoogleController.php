<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Google_Client;

class GoogleController extends Controller
{
    public function handleGoogleAuth(Request $request)
    {
        try {
            // Validar el token de Google
            $idToken = $request->input('id_token');

            $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
            $payload = $client->verifyIdToken($idToken);

            if (!$payload) {
                return response()->json(['error' => 'Token de Google inválido'], 401);
            }

            // Extraer datos del usuario desde el token
            $googleId = $payload['sub'];
            $email = $payload['email'];
            $name = $payload['name'];

            // Buscar un usuario existente con el google_id o el email
            $user = User::where('google_id', $googleId)
                ->orWhere('email', $email)
                ->first();

            if ($user) {
                // Si el usuario existe, actualizamos su google_id si no lo tiene
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleId]);
                }
            } else {
                // Si el usuario no existe, lo creamos
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'google_id' => $googleId,
                    'password' => bcrypt(uniqid()), // Generamos una contraseña aleatoria
                ]);
            }

            // Iniciar sesión con el usuario
            Auth::login($user, true);

            // Generar un token de API con Sanctum
            $token = $user->createToken('AppToken')->plainTextToken;

            // Devolver el token y los datos del usuario al frontend
            return response()->json([
                'token' => $token,
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            // Manejar errores
            Log::error('Error al iniciar sesión con Google: ' . $e->getMessage());
            return response()->json(['error' => 'Error al iniciar sesión con Google'], 500);
        }
    }
}