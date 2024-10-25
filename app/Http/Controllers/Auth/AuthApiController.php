<?php

namespace Sibas\Http\Controllers\Auth;
use Illuminate\Http\Request;
use Sibas\Entities\User;
use Sibas\Http\Controllers\Controller;
use \Firebase\JWT\JWT;

class AuthApiController extends Controller
{
    /**
     * @var user
     */
    protected $user;

    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (!$this->isValidCredentials($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = $this->generateToken($request->all());

        return response()->json(compact('token'));
    }

    private function isValidCredentials($credentials)
    {
        $this->user = User::where('username', $credentials['username'])->first();

        if (!$this->user || !password_verify($credentials['password'], $this->user->password))
            return false;

        return true;
    }

    private function generateToken($data)
    {
        $issued_at = time();
        $token = [
            "iss" => $data['issuer_claim'],
            "iat" => $issued_at,
            "exp" => $issued_at + 3600,
            "email" => $this->user->email
        ];

        return JWT::encode($token, $data['secret_key'], 'HS256');
    }
}