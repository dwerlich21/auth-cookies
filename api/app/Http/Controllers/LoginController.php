<?php

namespace App\Http\Controllers;

use App\Services\CookieManager;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController
{
    private const ACCESS_TOKEN_NAME = 'access-token';
    private const REFRESH_TOKEN_NAME = 'refresh-token';
    
    protected CookieManager $cookieManager;
    
    public function __construct(CookieManager $cookieManager)
    {
        $this->cookieManager = $cookieManager;
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
            'access' => $accessToken->plainTextToken,
            'refresh' => $refreshToken->plainTextToken,
        ];
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (! Auth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciais inválidas',
                ], 401);
            }

            $user = Auth::user();
            $tokens = $this->generateTokens($user);

            return response()->json([
                'user' => $user,
                'success' => true,
                'message' => 'Login realizado com sucesso',
            ])
                ->withCookie($this->cookieManager->createAccessTokenCookie($tokens['access']))
                ->withCookie($this->cookieManager->createRefreshTokenCookie($tokens['refresh']));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao realizar login: '.$e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        Auth::logout();

        return response()->json([
            'success' => true,
            'message' => 'Logout realizado com sucesso',
        ])
            ->withCookie($this->cookieManager->forgetAccessTokenCookie())
            ->withCookie($this->cookieManager->forgetRefreshTokenCookie());
    }
}
