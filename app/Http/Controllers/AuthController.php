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

		return response()->json([
			'message' => 'User created successfully!',
			'user' => $user
		], 201);
	}

	public function login(LoginRequest $request)
	{
		$credentials = $request->all(['email', 'password']);

		if (!$token = auth('api')->attempt($credentials)) {
			return response()->json(['message' => 'Unauthorized'], 401);
		}

		return response()->json([
			'access_token' => $token,
			'token_type' => 'bearer',
			'expires_in' => auth()->factory()->getTTL() * 60
		], 200);
	}

	public function me()
	{
		$user = auth()->user();
		return response()->json(['user' => $user], 200);
	}

	public function refresh()
	{
		$token = auth('api')->refresh();

		return response()->json(['token' => $token], 204);
	}

	public function logout()
	{
		auth('api')->logout();

		return response()->json(['message' => 'Logout success!'], 200);
	}
}
