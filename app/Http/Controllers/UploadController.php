<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/upload/file",
     *     tags={"upload"},
     *     summary="Upload files to the system.",
     *     operationId="uploadFile",
     *     description="Return path file on the system.",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="file", type="string", format="binary"),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property (
     *                  property="path",
     *                  type="string",
     *                  example="/images/emv4xlPGlave5OHNiENggdDa7j4VfiKPR6na2I6C.jpg"
     *              ),
     *              @OA\Property (
     *                  property="message",
     *                  type="string",
     *                  example="Image is uploaded."
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
     *     ),
     * )
     */
    public function index(Request $request)
    {
        try {
            $image = Storage::disk('public')->put('images',$request->file);
            return new JsonResponse(['path' => '/' . $image], 200);
        } catch (\Throwable $th) {
            report($th);
            throw new \HttpResponseException(
                response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => [
                        'file' => $th->getMessage(),
                    ]
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/upload/multiple-file",
     *     tags={"upload"},
     *     summary="Upload files to the system.",
     *     operationId="uploadMultipleFile",
     *     description="Return path file on the system.",
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *              type="object",
     *                  @OA\Property(
     *                      property="files[]",
     *                      type="array",
     *                      @OA\Items(type="string", format="binary")
     *                  )
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property (
     *                  property="message",
     *                  type="string",
     *                  example="Images is uploaded."
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
     *     ),
     * )
     */
    public function multiple(Request $request)
    {
        try {
            $result = [];
            foreach ($request->file('files') as $key => $file) {
                $image = Storage::disk('public')->put('images',$file);
                array_push($result, '/' . $image);
            }
            return new JsonResponse([
                'message' => 'Image is uploaded.'
            ]);
        } catch (\Throwable $th) {
            report($th);
            throw new \HttpResponseException(
                response()->json([
                    'message' => 'The given data was invalid.',
                    'errors'  => [
                        'file' => $th->getMessage(),
                    ]
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            );
        }
    }
}
