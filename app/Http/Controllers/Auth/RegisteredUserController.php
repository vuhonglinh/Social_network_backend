<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules;
use Laravel\Passport\Client;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required'],
        ], [
            'name.required' => 'Không được để trống!',
            'name.max' => 'hông được vượt quá :max ký tự.',
            'email.required' => 'Email không được để trống!',
            'email.string' => 'Email phải là một chuỗi!',
            'email.email' => 'Email không đúng định dạng!',
            'email.max' => 'Email không được vượt quá :max ký tự.',
            'email.unique' => 'Email đã được sử dụng, vui lòng chọn email khác.',
            'password.required' => 'Mật khẩu không được để trống!',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

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
            'message' => 'Đăng ký thành công',
            'data' => [
                'token' =>  $response->json(),
                'user' => $user
            ]
        ]);
    }
}