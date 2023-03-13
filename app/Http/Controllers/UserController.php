<?php

namespace App\Http\Controllers;

use App\Models\User;

/**
 * @OA\Info(
 *   title="API Test Liberfly",
 *   version="1.0.0",
 *   contact={
 *     "email": "eduardo.hor@outlook.com"
 *   }
 * )
 * @OA\SecurityScheme(
 *  type="http",
 *  description="Acess token obtido na autenticação",
 *  name="Authorization",
 *  in="header",
 *  scheme="bearer",
 *  bearerFormat="JWT",
 *  securityScheme="bearerToken"
 * )
 */
class UserController extends Controller
{
	private $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	public function index()
	{
		$users = $this->user->orderByDesc('created_at')->get();

		return response()->json($users, 200);
	}

	public function show($id)
	{
		if (!$user = $this->user->find($id)) {
			return response()->json(['message' => 'Usuário não encontrado!'], 404);
		}

		return response()->json(['user' => $user], 200);
	}
}
