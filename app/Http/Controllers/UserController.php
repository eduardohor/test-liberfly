<?php

namespace App\Http\Controllers;

use App\Models\User;

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
