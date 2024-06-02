<?php

namespace App\Http\Controllers\Book;

use Illuminate\Http\Request;
use App\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Validator;
use App\Services\CategoryServices;
use App\Helpers\ResponseJson;

class CategoryController extends CrudController
{
    public function __construct(CategoryServices $categoryService)
    {
        $this->service = $categoryService;
    }

    protected function generateMessage($data, $type = null)
    {
        $message = '';
        switch ($type) {
            case 'create':
                $message = 'Category created successfully';
                break;

            case 'update':
                $message = 'Category updated successfully';
                break;

            case 'delete':
                $message = 'Category deleted successfully';
                break;

            default:
                $message = 'Success';
                break;
        }

        return $message;
    }

    /**
     * @OA\Get(
     *      path="/categories/list",
     *      operationId="listCategory",
     *      tags={"API Endpoints"},
     *      summary="List Categories",
     *      description="Returns Categories data",
     *      security={{"bearerAuth":{}}},
    *       @OA\Response(
    *          response=200,
    *          description="Success",
    *          @OA\JsonContent(ref="#/components/schemas/ApiResponse")
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad request",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorResponse")
    *      ),
    *       @OA\Response(
    *          response=401,
    *          description="Unauthorized",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorUnAuth")
    *      )
     * )
    *
    *
    *
     * @OA\Get(
     *      path="/categories/find",
     *      operationId="findCategory",
     *      tags={"API Endpoints"},
     *      summary="Find a Category",
     *      description="Returns Category data",
     *      security={{"bearerAuth":{}}},
     *     @OA\Parameter(
    *         name="id",
    *         in="query",
    *         required=true,
    *         description="ID Category",
    *         @OA\Schema(
    *             type="integer"
    *         )
    *     ),
    *       @OA\Response(
    *          response=200,
    *          description="Success",
    *          @OA\JsonContent(ref="#/components/schemas/ApiResponse")
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad request",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorResponse")
    *      ),
    *       @OA\Response(
    *          response=401,
    *          description="Unauthorized",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorUnAuth")
    *      )
     * )
    *
    */

    public function runValidationShow($request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:categories,id'
        ]);

        return $validator;
    }

    /**
     * @OA\Post(
     *      path="/categories/create",
     *      operationId="createCategory",
     *      tags={"API Endpoints"},
     *      summary="Create a new Category",
     *      description="Returns Category data",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="IT"),
     *          ),
     *      ),
    *       @OA\Response(
    *          response=200,
    *          description="Category created successfully.",
    *          @OA\JsonContent(ref="#/components/schemas/ApiResponse")
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad request",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorResponse")
    *      ),
    *       @OA\Response(
    *          response=401,
    *          description="Unauthorized",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorUnAuth")
    *      )
     * )
    */

    public function runValidationCreate($request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|unique:categories,name|max:50',
        ]);

        return $validator;
    }

    /**
    * @OA\Put(
    *      path="/categories/update",
    *      operationId="updateCategory",
    *      tags={"API Endpoints"},
    *      summary="Update a Category",
    *      description="Returns Category data",
    *      security={{"bearerAuth":{}}},
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *              required={"id", "name"},
    *              @OA\Property(property="id", type="integer", example=9),
    *              @OA\Property(property="name", type="string", example="Agama"),
    *          )
    *      ),
    *       @OA\Response(
    *          response=200,
    *          description="Category updated successfully",
    *          @OA\JsonContent(ref="#/components/schemas/ApiResponse")
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad request",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorResponse")
    *      )
    * )
    */

    public function runValidationUpdate($request)
    {
        $validator = Validator::make($request->all(), [
            'id'  => 'required|unique:categories,id,'.$request->id.',id',
            'name'   => 'required|string|unique:categories,name|max:50',

        ]);

        return $validator;
    }

    /**
     * @OA\Delete(
     *      path="/categories/delete",
     *      operationId="deleteCategory",
     *      tags={"API Endpoints"},
     *      summary="Delete a Category",
     *      security={{"bearerAuth":{}}},
     *     @OA\Parameter(
    *         name="id",
    *         in="query",
    *         required=true,
    *         description="ID Category",
    *         @OA\Schema(
    *             type="integer"
    *         )
    *     ),
    *       @OA\Response(
    *          response=200,
    *          description="Success",
    *          @OA\JsonContent(ref="#/components/schemas/ApiResponse")
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad request",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorResponse")
    *      ),
    *       @OA\Response(
    *          response=401,
    *          description="Unauthorized",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorUnAuth")
    *      )
     * )
    */

    public function runValidationDestroy($request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:categories,id,deleted_at,NULL'
        ]);

        return $validator;
    }
}
