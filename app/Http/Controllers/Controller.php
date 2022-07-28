<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info (
 *     version="1.0.0",
 *     title="Laravel OpenApi V1 Demo Documentation",
 *     description="L5 Swagger OpenApi description",
 *     termsOfService="http://swagger.io/terms/",
 *     @OA\Contact(
 *         email="admin@admin.com"
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Demo API Server"
 * )
 *
 *  @OA\Server(
 *      url="http://127.0.0.1:8000/api/",
 *      description="Development Environment"
 *  )
 *
 *  @OA\Server(
 *      url="http://127.0.0.1:9000/api/",
 *      description="Staging  Environment"
 * )
 * @OA\Tag(
 *     name="auth",
 *     description="Operations about auth user",
 *     @OA\ExternalDocumentation(
 *         description="Find out more about store",
 *         url="http://swagger.io"
 *     )
 * )
 * @OA\Tag(
 *     name="user",
 *     description="Operations about user",
 *     @OA\ExternalDocumentation(
 *         description="Find out more about store",
 *         url="http://swagger.io"
 *     )
 * )
 * @OA\Tag(
 *     name="upload",
 *     description="Operations about file",
 *     @OA\ExternalDocumentation(
 *         description="Find out more about store",
 *         url="http://swagger.io"
 *     )
 * )
 * @OA\Tag(
 *     name="projects",
 *     description="Operations about projects",
 *     @OA\ExternalDocumentation(
 *         description="Find out more about store",
 *         url="http://swagger.io"
 *     )
 * )
 * @OA\ExternalDocumentation(
 *     description="Find out more about Swagger",
 *     url="http://swagger.io"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
