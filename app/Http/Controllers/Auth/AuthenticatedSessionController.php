<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Laravel\Passport\TokenRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate(); //Bảo vệ phiên làm việc
        $user =  $request->user();
        $client = Client::where('password_client', true)->first();
        $response = Http::asForm()->post('http://127.0.0.1:8001/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $request->email,
            'password' => $user->password,
            'scope' => '*',
        ]);

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'data' => [
                'token' =>  $response->json(),
                'user' => $user,
            ]
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        $user = $request->user(); // Lấy user hiện tại

        // Lấy repository của Token và RefreshToken
        $tokenRepository = App::make(TokenRepository::class);
        $refreshTokenRepository = App::make(\Laravel\Passport\RefreshTokenRepository::class);

        // Thu hồi access token
        $accessTokenId = $user->token()->id;
        $tokenRepository->revokeAccessToken($accessTokenId);

        // Thu hồi tất cả refresh tokens liên quan đến access token
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($accessTokenId);

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json([
            'message' => 'Đăng xuất thành công',
        ], 200)->withoutCookie('remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
    }


    public function rememberMe(Request $request)
    {
        $rememberToken = $request->cookie('remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
        $segments = explode('|', $rememberToken);
        $userId = $segments[0];
        $token = $segments[1];
        $password = $segments[2];
        if ($rememberToken) {
            $user = User::findOrFail($userId);
            if ($user && hash_equals($user->getRememberToken(), $token)) {
                // Đăng nhập thành công bằng ghi nhớ đăng nhập
                $client = Client::where('password_client', true)->first();
                $response = Http::asForm()->post('http://127.0.0.1:8001/oauth/token', [
                    'grant_type' => 'password',
                    'client_id' => $client->id,
                    'client_secret' => $client->secret,
                    'username' => $user->email,
                    'password' => $password,
                    'scope' => '*',
                ]);
                return response()->json([
                    'message' => 'Đăng nhập thành công',
                    'data' => [
                        'token' =>  $response->json(),
                        'user' => $user,
                    ]
                ], 200);
            }
        }
        return response()->noContent();
    }

    public function googleCallback(Request $request)
    {
        return $request->json();
    }
}