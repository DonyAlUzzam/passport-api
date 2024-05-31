<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use OpenApi\Generator;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Laravel Passport API Documentation",
 *      description="API documentation for Laravel Passport",
 * )
 * 
 * @OA\Server(
 *      url="http://localhost:8000/api",
 *      description="API Server"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your Bearer token "
 * )
 *
 * @OA\Components(
 *     @OA\Schema(
 *         schema="ApiResponse",
 *         type="object",
 *         @OA\Property(
 *             property="status",
 *             type="string",
 *             example="success"
 *         ),
 *         @OA\Property(
 *             property="status_code",
 *             type="number",
 *             example=200
 *         ),
 *         @OA\Property(
 *             property="message",
 *             type="string",
 *             example="Operation completed successfully"
 *         ),
 *         @OA\Property(
 *             property="data",
 *             type="object"
 *         )
 *     ),
 *     @OA\Schema(
 *         schema="ApiErrorResponse",
 *         type="object",
 *         @OA\Property(
 *             property="status",
 *             type="string",
 *             example="error"
 *         ),
 *         @OA\Property(
 *             property="status_code",
 *             type="number",
 *             example=400
 *         ),
 *         @OA\Property(
 *             property="message",
 *             type="object",
 *             example={"erors": "error message"}
 *         ),
 *         @OA\Property(
 *             property="data",
 *             type="object",
 *             example=null
 *         )
 *     ),
 *      @OA\Schema(
 *         schema="ApiErrorUnAuth",
 *         type="object",
 *         @OA\Property(
 *             property="status",
 *             type="string",
 *             example="error"
 *         ),
 *         @OA\Property(
 *             property="status_code",
 *             type="number",
 *             example=401
 *         ),
 *         @OA\Property(
 *             property="message",
 *             type="string",
 *             example="Unauthorized"
 *         ),
 *         @OA\Property(
 *             property="data",
 *             type="object",
 *             example=null
 *         )
 *     ),
 * )
 * 
 */



class SwaggerController extends BaseController
{
    public function json()
    {
        $openapi = Generator::scan([app_path('Http/Controllers')]);

        // dd($openapi);
        return response()->json($openapi);
    }
}
