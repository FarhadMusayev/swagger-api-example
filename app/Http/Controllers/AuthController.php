<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @OAS\SecurityScheme(
 *      securityScheme="bearer_token",
 *      type="http",
 *      scheme="bearer"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(path="/api/v1/login",
     *   tags={"auth"},
     *   summary="Post your email and password and we will return a token. Use the token in the 'Authorization' header like so 'Bearer YOUR_TOKEN'",
     *   operationId="login",
     *   description="Post your email and password and we will return a token.",
     *   @OA\RequestBody(
     *       required=true,
     *       description="The Token Request",
     *       @OA\JsonContent(
     *        @OA\Property(property="email",type="string",example="your@email.com"),
     *        @OA\Property(property="password",type="string",example="YOUR_PASSWORD"),
     *       )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *          type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="string",
     *                  example="true"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  example={
     *                      "name": "Rehim Alcanov",
     *                      "email": "rehimalcanov@mail.ru",
     *                      "updated_at": "2022-07-20T10:57:41.000000Z",
     *                      "created_at": "2022-07-20T10:57:41.000000Z",
     *                      "id": "4",
     *                  },
     *              ),
     *              @OA\Property(
     *                  property="token",
     *                  type="string",
     *                  example="5|J9pEuB8PtCd2gNY24kkMLRthbbfI884UE0be7QSc"
     *              )
     *      )
     *   ),
     *   @OA\Response(response=422, description="The provided credentials are incorrect."),
     *   @OA\Response(response=401, description="The provided credentials are unauthorized.")
     * )
     */
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Invalid credentials.'
            ], 401);
        }

        $user = \auth()->user();
        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/auth/user",
     *     tags={"auth"},
     *     summary="Get user by id",
     *     description="Returns a single new user.",
     *     operationId="getUserByToken",
     *     security={ {"bearer_token": {} }},
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *      @OA\JsonContent(
     *          type="object",
     *              @OA\Property(
     *                  property="user",
     *                  type="object",
     *                  example={
     *                      "id": "4",
     *                      "name": "Rehim Alcanov",
     *                      "email": "rehimalcanov@mail.ru",
     *                      "email_verified_at":"2022-07-20T10:24:56.000000Z",
     *                      "updated_at": "2022-07-20T10:57:41.000000Z",
     *                      "created_at": "2022-07-20T10:57:41.000000Z",
     *                  },
     *              )
     *          )
     *      ),
     *   @OA\Response(response=401, description="The provided credentials are unauthorized.")
     * )
     */
    public function authToken()
    {
        $user = request()->user();

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/logout",
     *     tags={"auth"},
     *     summary="Logout User",
     *     description="This can only be done by the logged in user.",
     *     operationId="logoutUser",
     *     security={ {"bearer_token": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="string",
     *                  example="true"
     *              ),
     *         ),
     *     )
     * )
     */
    public function logout()
    {
        \request()->user()->currentAccessToken()->delete();

        return response()->json(['success' => true]);
    }

    /**
     * @OA\Post(
     *   path="/api/v1/register",
     *   tags={"auth"},
     *   summary="Post your email and password and we will return a token. Use the token in the 'Authorization' header like so 'Bearer YOUR_TOKEN'",
     *   operationId="register",
     *   description="Post your email and password and we will return a token.",
     *   @OA\RequestBody(
     *       required=true,
     *       description="The Token Request",
     *       @OA\JsonContent(
     *        @OA\Property(property="name",type="string",example="your@email.com"),
     *        @OA\Property(property="email",type="string",example="your@email.com"),
     *        @OA\Property(property="password",type="string",example="YOUR_PASSWORD"),
     *       )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *          type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="string",
     *                  example="true"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  example={
     *                      "name": "Rehim Alcanov",
     *                      "email": "rehimalcanov@mail.ru",
     *                      "updated_at": "2022-07-20T10:57:41.000000Z",
     *                      "created_at": "2022-07-20T10:57:41.000000Z",
     *                      "id": "4",
     *                  },
     *              ),
     *              @OA\Property(
     *                  property="token",
     *                  type="string",
     *                  example="5|J9pEuB8PtCd2gNY24kkMLRthbbfI884UE0be7QSc"
     *              )
     *      )
     *   ),
     *   @OA\Response(response=422, description="The provided credentials are incorrect."),
     *   @OA\Response(response=401, description="The provided credentials are unauthorized.")
     * )
     */

    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => $user,
            'token' => $token
        ]);
    }
}
