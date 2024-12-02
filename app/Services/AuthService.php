<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Passport;

class AuthService
{
    protected $clientId, $clientSecret;

    private function checkPassportConfiguration()
    {
        $passportCredentials = config('passport.password_client');
        [$this->clientId, $this->clientSecret] = [$passportCredentials['id'], $passportCredentials['secret']];
        if (!@$this->clientId || !@$this->clientSecret) {
            Log::alert('Passport credentials not initialized');
            Log::alert('Please run "php artisan passport:token-install"');
            return response()->json([
                'statusCode' => 500,
                'message' => "Something went wrong",
            ]);
        }
    }

    public function login($data)
    {
        $response = $this->checkPassportConfiguration();
        if (@$response)
            return $response;

        $email = $data['email'];
        $password = $data['password'];

        $user = User::where('email', $email)->first();

        if (!@$user) {
            return response()->json([
                'statusCode' => 401,
                'message' => "Credentials does not match",
            ]);
        }

        if (@$user && Hash::check($password, $user->password)) {
            $token = $this->generateToken($email, $password);
            return response()->json([
                'statusCode' => 200,
                'message' => "Login Successful",
                "data" => [
                    "user" => [
                        'fullName' => $user->name,
                        'email' => $user->email
                    ],
                    "_token" => [
                        "accessToken" => $token['access_token'],
                        'refreshToken' => $token['refresh_token'],
                    ]
                ]
            ]);
        }

        return response()->json([
            'statusCode' => 401,
            'message' => "Credentials does not match",
        ]);
    }

    public function logout()
    {
        $authUser = auth('api')->user();
        if ($authUser)
            $authUser->tokens()->delete();

        return response()->json([
            "statusCode" => 204,
            "message" => "Logout successfully"
        ], 204);
    }

    private function generateToken($email, $password)
    {
        try {

            $response = Http::post(env('APP_URL') . '/oauth/token', [
                'grant_type' => 'password',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'username' => $email,
                'password' => $password,
                'scope' => '',
            ]);
            return $response->json();
        } catch (Exception $e) {
            Log::critical($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
