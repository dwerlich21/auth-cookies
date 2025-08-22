<?php

namespace App\Http\Middleware;

use App\Services\CookieManager;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class CookieToTokenMiddleware
{
    protected CookieManager $cookieManager;
    
    public function __construct(CookieManager $cookieManager)
    {
        $this->cookieManager = $cookieManager;
    }
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se há Authorization header
        if (!$request->hasHeader('Authorization')) {
            $accessToken = $this->cookieManager->getAccessTokenFromRequest($request);
            $refreshToken = $this->cookieManager->getRefreshTokenFromRequest($request);

            Log::debug('CookieToTokenMiddleware: processando request', [
                'has_access_token' => !empty($accessToken),
                'has_refresh_token' => !empty($refreshToken),
                'url' => $request->url()
            ]);

            $cookiesToAdd = [];

            if ($accessToken) {
                $validToken = $this->checkAccessToken($accessToken, $refreshToken);
                
                if ($validToken) {
                    $request->headers->set('Authorization', 'Bearer ' . $validToken);
                    Log::debug('CookieToTokenMiddleware: Authorization header adicionado');
                    
                    // Renovar cookies com os mesmos tokens mas tempo de validade atualizado
                    $cookiesToAdd[] = $this->cookieManager->createAccessTokenCookie($accessToken);
                    if ($refreshToken) {
                        $cookiesToAdd[] = $this->cookieManager->createRefreshTokenCookie($refreshToken);
                    }
                } else {
                    Log::debug('CookieToTokenMiddleware: Token inválido ou expirado, não foi possível renovar');
                }
            } else if ($refreshToken) {
                // Se não há access token mas há refresh token, tentar criar novo access token
                Log::debug('CookieToTokenMiddleware: Tentando criar novo access token usando refresh token');
                $newAccessToken = $this->createNewAccessToken($refreshToken);
                
                if ($newAccessToken) {
                    $request->headers->set('Authorization', 'Bearer ' . $newAccessToken);
                    Log::debug('CookieToTokenMiddleware: Novo access token criado e Authorization header adicionado');
                    
                    // Adicionar os cookies com os tokens
                    $cookiesToAdd[] = $this->cookieManager->createAccessTokenCookie($newAccessToken);
                    $cookiesToAdd[] = $this->cookieManager->createRefreshTokenCookie($refreshToken);
                } else {
                    Log::debug('CookieToTokenMiddleware: Não foi possível criar novo access token com refresh token');
                }
            } else {
                Log::debug('CookieToTokenMiddleware: Nenhum token encontrado nos cookies');
            }

            // Se temos cookies para adicionar, adiciona-los na resposta
            if (!empty($cookiesToAdd)) {
                $response = $next($request);
                foreach ($cookiesToAdd as $cookie) {
                    $response = $response->withCookie($cookie);
                }
                return $response;
            }
        } else {
            Log::debug('CookieToTokenMiddleware: Authorization header já presente, pulando processamento');
        }

        return $next($request);
    }

    private function getTokenRecord(?string $plainToken, string $name = null)
    {
        if (!$plainToken || !str_contains($plainToken, '|')) {
            return null;
        }

        try {
            // Sanctum armazena o hash do token, não o texto plano
            [$id, $token] = explode('|', $plainToken, 2);
            
            if (!is_numeric($id) || empty($token)) {
                return null;
            }
            
            $query = PersonalAccessToken::where('id', $id);
            
            if ($name) {
                $query->where('name', $name);
            }
            
            $tokenRecord = $query->first();
            
            // Verificar se o hash do token corresponde
            if ($tokenRecord && hash_equals($tokenRecord->token, hash('sha256', $token))) {
                return $tokenRecord;
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Erro ao buscar token record: ' . $e->getMessage());
            return null;
        }
    }

    private function checkAccessToken($accessToken, $refreshToken)
    {
        try {
            $now = Carbon::now();
            $tokenRecord = $this->getTokenRecord($accessToken, 'access-token');

            if (!$tokenRecord) {
                Log::debug('Access token record não encontrado no banco');
                return null;
            }

            // Verificar se o token expirou
            if ($tokenRecord->expires_at && $tokenRecord->expires_at->isPast()) {
                Log::debug('Access token expirado, tentando renovar com refresh token', [
                    'expired_at' => $tokenRecord->expires_at->toDateTimeString(),
                    'now' => $now->toDateTimeString()
                ]);
                // Tentar renovar com refresh token
                return $this->checkRefreshToken($refreshToken, $accessToken);
            }

            // Atualizar last_used_at e renovar expiração do access token
            $tokenRecord->update([
                'last_used_at' => $now,
                'expires_at' => $now->copy()->addMinutes(10)
            ]);

            // Também renovar o refresh token se existir
            if ($refreshToken) {
                $refreshTokenRecord = $this->getTokenRecord($refreshToken, 'refresh-token');
                if ($refreshTokenRecord) {
                    $refreshTokenRecord->update([
                        'last_used_at' => $now,
                        'expires_at' => $now->copy()->addDay()
                    ]);
                }
            }

            Log::debug('Access token válido e renovado', [
                'expires_at' => $tokenRecord->expires_at->toDateTimeString()
            ]);

            return $accessToken; // Retornar o token original (plain text)
            
        } catch (\Exception $e) {
            Log::error('Erro ao verificar access token: ' . $e->getMessage());
            return null;
        }
    }

    private function checkRefreshToken($refreshToken, $accessToken)
    {
        try {
            if (!$refreshToken) {
                Log::debug('Refresh token não encontrado no cookie');
                return null;
            }

            $now = Carbon::now();
            $refreshTokenRecord = $this->getTokenRecord($refreshToken, 'refresh-token');

            if (!$refreshTokenRecord) {
                Log::debug('Refresh token record não encontrado no banco');
                return null;
            }

            if ($refreshTokenRecord->expires_at && $refreshTokenRecord->expires_at->isPast()) {
                Log::debug('Refresh token expirado');
                return null;
            }

            // Buscar o access token record para atualizar
            $accessTokenRecord = $this->getTokenRecord($accessToken, 'access-token');
            
            if (!$accessTokenRecord) {
                Log::debug('Access token record não encontrado para renovação');
                return null;
            }

            // Renovar o access token
            $accessTokenRecord->update([
                'last_used_at' => $now,
                'expires_at' => $now->copy()->addMinutes(10)
            ]);

            // Atualizar o refresh token também
            $refreshTokenRecord->update([
                'last_used_at' => $now,
                'expires_at' => $now->copy()->addDay()
            ]);

            Log::debug('Tokens renovados com sucesso via refresh token');
            
            // IMPORTANTE: Retornar o access token original após renovação
            return $accessToken;
            
        } catch (\Exception $e) {
            Log::error('Erro ao verificar refresh token: ' . $e->getMessage());
            return null;
        }
    }

    private function createNewAccessToken($refreshToken)
    {
        try {
            if (!$refreshToken || !str_contains($refreshToken, '|')) {
                Log::debug('Refresh token inválido ou mal formatado');
                return null;
            }

            $now = Carbon::now();
            $refreshTokenRecord = $this->getTokenRecord($refreshToken, 'refresh-token');

            if (!$refreshTokenRecord) {
                Log::debug('Refresh token record não encontrado');
                return null;
            }

            if ($refreshTokenRecord->expires_at && $refreshTokenRecord->expires_at->isPast()) {
                Log::debug('Refresh token expirado, não é possível criar novo access token');
                return null;
            }

            // Obter o usuário associado ao refresh token
            $user = $refreshTokenRecord->tokenable;
            
            if (!$user) {
                Log::debug('Usuário não encontrado para o refresh token');
                return null;
            }

            // Criar novo access token
            $newAccessToken = $user->createToken(
                'access-token',
                ['*'],
                $now->copy()->addMinutes(CookieManager::ACCESS_TOKEN_EXPIRY)
            );

            // Atualizar o refresh token
            $refreshTokenRecord->update([
                'last_used_at' => $now,
                'expires_at' => $now->copy()->addMinutes(CookieManager::REFRESH_TOKEN_EXPIRY)
            ]);

            Log::debug('Novo access token criado com sucesso', [
                'user_id' => $user->id,
                'expires_at' => $now->copy()->addMinutes(CookieManager::ACCESS_TOKEN_EXPIRY)->toDateTimeString()
            ]);

            return $newAccessToken->plainTextToken;
            
        } catch (\Exception $e) {
            Log::error('Erro ao criar novo access token: ' . $e->getMessage());
            return null;
        }
    }
}
