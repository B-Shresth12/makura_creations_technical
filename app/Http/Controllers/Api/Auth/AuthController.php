<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    function __construct(protected AuthService $authService) {}
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        return $this->authService->login($data);
    }

    public function logout(){
        return $this->authService->logout();
    }
}
