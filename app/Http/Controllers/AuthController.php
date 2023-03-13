<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

	public function register(UserRequest $request)
	{
		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => Hash::make($request->password)
		]);

		return $user;

	}

	public function login(LoginRequest $request)
	{
		$credentials = $request->all(['email', 'password']);

		if (!$token = auth('api')->attempt($credentials)) {
			return response()->json(['error' => 'Unauthorized'], 401);
		}

		return response()->json([
			'access_token' => $token,
			'token_type' => 'bearer',
			'expires_in' => auth()->factory()->getTTL() * 60
		]);
	}
	
	public function me()
	{

		return response()->json(auth()->user());

	}

	public function refresh()
	{
		$token = auth('api')->refresh();

		return response()->json(['token' => $token]);
	}

	public function logout()
	{
		auth('api')->logout();

		return response()->json(['message' => 'Logout success!']);
	}
}
