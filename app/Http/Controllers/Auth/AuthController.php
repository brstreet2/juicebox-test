<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request)
    {
        return $this->userService->register($request->validated());
    }

    public function login(LoginRequest $request)
    {
        return $this->userService->login($request->validated());
    }

    public function logout(Request $request)
    {
        return $this->userService->logout($request->user());
    }

    public function show(int $id)
    {
        return $this->userService->show($id);
    }
}
