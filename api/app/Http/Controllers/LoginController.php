<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\RecoverPasswordRequest;
use App\Services\CookieManager;
use App\Services\UserService;
use App\Traits\ExceptionHandlerTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController
{
    use ExceptionHandlerTrait;

    private const ACCESS_TOKEN_NAME = 'access-token';
    private const REFRESH_TOKEN_NAME = 'refresh-token';

    protected CookieManager $cookieManager;
    protected UserService $userService;

    public function __construct(CookieManager $cookieManager, UserService $userService)
    {
        $this->cookieManager = $cookieManager;
        $this->userService = $userService;
    }

    private function generateTokens($user): array
    {
        // Revoke all existing tokens
        $user->tokens()->delete();

        // Create new tokens
        $accessToken = $user->createToken(
            self::ACCESS_TOKEN_NAME,
            ['*'],
            Carbon::now()->addMinutes(CookieManager::ACCESS_TOKEN_EXPIRY)
        );

        $refreshToken = $user->createToken(
            self::REFRESH_TOKEN_NAME,
            ['*'],
            Carbon::now()->addMinutes(CookieManager::REFRESH_TOKEN_EXPIRY)
        );

        return [
            'access'  => $accessToken->plainTextToken,
            'refresh' => $refreshToken->plainTextToken,
        ];
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $credentials = $request->validate([
                'email'    => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciais inválidas',
                ], 401);
            }

            $user = Auth::user();
            $tokens = $this->generateTokens($user);

            return response()->json([
                'user'    => $user,
                'success' => true,
                'message' => 'Login realizado com sucesso',
            ])
                ->withCookie($this->cookieManager->createAccessTokenCookie($tokens['access']))
                ->withCookie($this->cookieManager->createRefreshTokenCookie($tokens['refresh']));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao realizar login: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        if ($request->user()) {
            // Revoke all tokens for the user
            $request->user()->tokens()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout realizado com sucesso',
        ])
            ->withCookie($this->cookieManager->forgetAccessTokenCookie())
            ->withCookie($this->cookieManager->forgetRefreshTokenCookie());
    }

    public function recoverPassword(RecoverPasswordRequest $request): JsonResponse
    {
        return $this->handleWithoutTransaction(function () use ($request) {

            $data = $request->all();
            $this->userService->recoverPassword($data);

            return $this->successResponse(null, 'Senha alterada com sucesso');

        }, 'Erro ao buscar registros');

    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        return $this->handleWithoutTransaction(function () use ($request) {

            $data = $request->all();
            $this->userService->forgotPassword($data);

            return $this->successResponse(null, 'Foi enviado um link para redefinição de senha para o e-mail informado');

        }, 'Erro ao buscar registros');

    }
}
