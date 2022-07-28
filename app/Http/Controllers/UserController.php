<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"user"},
     *     summary="Get list user",
     *     description="Returns a single new user.",
     *     operationId="getListUser",
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      example={
     *                          "id": 1,
     *                          "name": "ManhDan",
     *                          "email": "danhuynh660@gmail.com",
     *                      },
     *                  )
     *              ),
     *              @OA\Property(
     *                  property="links",
     *                  type="object",
     *                  example={
     *                      "first": "http://127.0.0.1:8000/api/users?page=1",
     *                      "last": "http://127.0.0.1:8000/api/users?page=1",
     *                      "prev": null,
     *                      "next": null,
     *                  },
     *              ),
     *               @OA\Property(
     *                  property="meta",
     *                  type="object",
     *                  @OA\Property(
     *                      property="current_page",
     *                      type="integer",
     *                      example=1
     *                  ),
     *                  @OA\Property(
     *                      property="from",
     *                      type="integer",
     *                      example=1
     *                  ),
     *                  @OA\Property(
     *                      property="last_page",
     *                      type="integer",
     *                      example=1
     *                  ),
     *                  @OA\Property(
     *                      property="links",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          example={
     *                              "url": "http://127.0.0.1:8000/api/users?page=1",
     *                              "label": "http://127.0.0.1:8000/api/users?page=1",
     *                              "active": null,
     *                          },
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="path",
     *                      type="string",
     *                      example="http://127.0.0.1:8000/api/users"
     *                  ),
     *                  @OA\Property(
     *                      property="per_page",
     *                      type="integer",
     *                      example=10
     *                  ),
     *                  @OA\Property(
     *                      property="to",
     *                      type="integer",
     *                      example=10
     *                  ),
     *                  @OA\Property(
     *                      property="total",
     *                      type="integer",
     *                      example=10
     *                  ),
     *              ),
     *          )
     *      ),
     * )
     */
    public function index()
    {
        $users = User::select(['id', 'name', 'email'])->paginate(10);
        return new UserCollection($users);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"user"},
     *     summary="Add a new user to the store",
     *     description="Returns a single new user.",
     *     operationId="createUser",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/User"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid id supplied",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="The specified data is invalid."
     *              ),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  example={
     *                      "name": "The name field is required.",
     *                  },
     *              ),
     *         ),
     *     ),
     * )
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);
        return new UserResource($user);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{userId}",
     *     tags={"user"},
     *     summary="Get user by user id",
     *     operationId="getUserById",
     *     description="Returns a single user.",
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/User"),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="User not found."
     *              ),
     *         ),
     *     ),
     * )
     */
    public function show($id)
    {
        $user = User::where('id', $id)->first();
        if ($user) {
            return new UserResource($user);
        }
        return response()->json(['message' => 'User not found.'], JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{userId}",
     *     tags={"user"},
     *     summary="Updated user",
     *     description="Returns a single user.",
     *     operationId="updateUser",
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="User that to be updated",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *          description= "User object needs to be updated to the store.",
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="password", type="string")
     *          )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="name", type="string"),
     *          )
     *      ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid id supplied",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="The specified data is invalid."
     *              ),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  example={
     *                      "name": "The name field is required.",
     *                  },
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="User not found."
     *              ),
     *         ),
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        if ($user) {
            $user->email = $request->email;
            $user->password = $request->password;
            $user->save();
            return response()->json(['message' => 'Update user successfully.'], JsonResponse::HTTP_OK);
        }
        return response()->json(['message' => 'User not found.'], JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{userId}",
     *     tags={"user"},
     *     summary="Delete user",
     *     description="This can only be done by the logged in user.",
     *     operationId="deleteUser",
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="The name that needs to be deleted",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Delete user successfully."
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="User not found."
     *              ),
     *         ),
     *     )
     * )
     */
    public function destroy($id)
    {
        $user = User::where('id', $id)->first();
        if ($user) {
            $user->delete();
            return response()->json(['message' => 'Delete user successfully.'], JsonResponse::HTTP_OK);
        }
        return response()->json(['message' => 'User not found.'], JsonResponse::HTTP_NOT_FOUND);
    }
}
