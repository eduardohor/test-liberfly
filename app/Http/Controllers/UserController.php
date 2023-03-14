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

	/**
	 * @OA\Get(
	 *      tags={"User"},
	 *      summary="Get data from all users",
	 *      description="This endpoint returns data for all registered users.",
	 *      path="/api/users",
	 * 			security={ {"bearerToken":{}} },
	 * 			 @OA\Response(
	 *          response=200,
	 *          description="authenticated user data",
	 *          @OA\JsonContent(
	 *              @OA\Property(property="id", type="string", example="1"),
	 *              @OA\Property(property="name", type="string", example="Eduardo Henrique"),
	 *              @OA\Property(property="email", type="string", example="eduardo@example.org"),
	 *              @OA\Property(property="email_verified_at", type="string", example="null"),
	 *              @OA\Property(property="created_at", type="date", example="2023-03-13T17:33:25.000000Z"),
	 *              @OA\Property(property="updated_at", type="date", example="2023-03-13T17:33:25.000000Z"),
	 * 
	 *          )
	 *     ),
	 * 
	 *     @OA\Response(
	 *          response=401,
	 *          description="Unauthenticated",
	 *          @OA\JsonContent(
	 *              @OA\Property(property="message", type="string", example="Token not provided"),
	 *          )
	 *     )
	 * )
	 */

	public function index()
	{
		$users = $this->user->orderByDesc('created_at')->get();

		return response()->json($users, 200);
	}

	/**
	 * @OA\Get(
	 *      tags={"User"},
	 *      summary="Get specific user information",
	 *      description="This endpoint returns data for a specific user",
	 *      path="/api/users/{id}",
	 * 			security={ {"bearerToken":{}} },
	 *      @OA\Parameter(
	 *          name="id",
	 *          description="User Id",
	 *          required=true,
	 *          in="path",
	 *          @OA\Schema(
	 *              type="integer"
	 *          )
	 *      ),
	 * 			 @OA\Response(
	 *          response=200,
	 *          description="authenticated user data",
	 *          @OA\JsonContent(
	 *              @OA\Property(property="id", type="string", example="1"),
	 *              @OA\Property(property="name", type="string", example="Eduardo Henrique"),
	 *              @OA\Property(property="email", type="string", example="eduardo@example.org"),
	 *              @OA\Property(property="email_verified_at", type="string", example="null"),
	 *              @OA\Property(property="created_at", type="date", example="2023-03-13T17:33:25.000000Z"),
	 *              @OA\Property(property="updated_at", type="date", example="2023-03-13T17:33:25.000000Z"),
	 * 
	 *          )
	 *     ),
	 * 
	 *     @OA\Response(
	 *          response=401,
	 *          description="Unauthenticated",
	 *          @OA\JsonContent(
	 *              @OA\Property(property="message", type="string", example="Token not provided"),
	 *          )
	 *     ),
	 * 
	 *     @OA\Response(
	 *          response=404,
	 *          description="Not Found",
	 *          @OA\JsonContent(
	 *              @OA\Property(property="message", type="string", example="User not found"),
	 *          )
	 *     )
	 * )
	 */

	public function show($id)
	{
		if (!$user = $this->user->find($id)) {
			return response()->json(['message' => 'User not found!'], 404);
		}

		return response()->json(['user' => $user], 200);
	}
}
