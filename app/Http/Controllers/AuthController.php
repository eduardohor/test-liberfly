<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

	/**
	 * @OA\POST(
	 *  tags={"User"},
	 *  summary="Create a new user",
	 *  description="This endpoint creates a new user",
	 *  path="/api/register",
	 *  @OA\RequestBody(
	 *      @OA\MediaType(
	 *          mediaType="application/x-www-form-urlencoded",
	 *          @OA\Schema(
	 *             required={"email","name","password","password_confirmation"},
	 *             @OA\Property(property="name", type="string", example="Eduardo Henrique"),
	 *             @OA\Property(property="email", type="string", example="eduardohenrique@example.org"),
	 *             @OA\Property(property="password", type="string", example="123456"),
	 *             @OA\Property(property="password_confirmation", type="string", example="123456"),
	 *          )
	 *      ),
	 *  ),
	 *  @OA\Response(
	 *    response=201,
	 *    description="User created",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="User created successfully!"),
	 * 			@OA\Property(property="user", type="string", example="..."),
	 *    )
	 *  ),
	 *  @OA\Response(
	 *    response=422,
	 *    description="Incorrect fields",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="The e-mail has already been taken. (and 2 more errors)"),
	 *       @OA\Property(property="errors", type="string", example="..."),
	 *    )
	 *  )
	 * )
	 */

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

	/**
	 * @OA\POST(
	 *  tags={"JWT Authentication"},
	 *  summary="Get a autentication user token",
	 *  description="This endpoint returns a new user authentication token for use on secured endpoints",
	 *  path="/api/login",
	 *  @OA\RequestBody(
	 *      @OA\MediaType(
	 *          mediaType="application/x-www-form-urlencoded",
	 *          @OA\Schema(
	 *              required={"email","password"},
	 *              @OA\Property(property="email", type="string", example="eduardo@example.com"),
	 *              @OA\Property(property="password", type="string", example="!23@56")
	 *          )
	 *      ),
	 *  ),
	 *  @OA\Response(
	 *    response=200,
	 *    description="Token generated",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNjc4NzQwMDYwLCJleHAiOjE2Nzg3NDM2NjAsIm5iZiI6MTY3ODc0MDA2MCwianRpIjoiVzFhYmczeE1Bd0RPNjkxVyIsInN1YiI6IjU4IiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.-6UD5s4-E8aag44EjhZUSQzU-_NQfYp81m0QfpdL-2g"),
	 * 			@OA\Property(property="token_type", type="string", example="bearer"),
	 * 			@OA\Property(property="expires_in", type="string", example= 3600)
	 *    )
	 *  ),
	 *  @OA\Response(
	 *    response=401,
	 *    description="Incorrect credentials",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="Unauthorized")
	 *    )
	 *  )
	 * )
	 */

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

	/**
	 * @OA\Get(
	 *     tags={"User"},
	 *     summary="Get data about authenticated user",
	 *     description="This endpoint returns all authenticated user data",
	 *     path="/api/me",
	 *     security={ {"bearerToken":{}} },
	 *     @OA\Response(
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
	 *     @OA\Response(
	 *          response=401,
	 *          description="Unauthenticated",
	 *          @OA\JsonContent(
	 *              @OA\Property(property="message", type="string", example="Token not provided"),
	 *          )
	 *     )
	 * )
	 */

	public function me()
	{
		$user = auth()->user();
		return response()->json(['user' => $user], 200);
	}

	/**
	 * @OA\GET(
	 *  tags={"JWT Authentication"},
	 *  summary="Revoke all user tokens",
	 *  description="This endpoint provides a logout for user, revoking all actived user tokens.",
	 *  path="/api/logout",
	 *  security={ {"bearerToken":{}} },
	 *  @OA\Response(
	 *    response=200,
	 *    description="All user tokens revoked",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="Logout success!")
	 *    )
	 *  ),
	 *  @OA\Response(
	 *    response=401,
	 *    description="Unauthenticated",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="The token has been blacklisted"),
	 *    )
	 *  )
	 * )
	 */

	public function logout()
	{
		auth('api')->logout();

		return response()->json(['message' => 'Logout success!'], 200);
	}
}
