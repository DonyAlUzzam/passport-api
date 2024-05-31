<?php

namespace App\Http\Controllers\Book;

use Illuminate\Http\Request;
use App\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Validator;
use App\Services\BookServices;
use App\Helpers\ResponseJson;

class BookController extends CrudController
{
    public function __construct(BookServices $bookService)
    {
        $this->service = $bookService;
      
    }

    protected function generateMessage($data, $type = null)
    {
        $message = '';
        switch ($type) {
            case 'create':
                $message = 'Book created successfully';
                break;

            case 'update':
                $message = 'Book updated successfully';
                break;

            case 'delete':
                $message = 'Book deleted successfully';
                break;

            default:
                $message = 'Success';
                break;
        }

        return $message;
    }

     /**
     * @OA\Get(
     *      path="/books/list",
     *      operationId="listBooks",
     *      tags={"API Endpoints for Admin"},
     *      summary="List Books",
     *      description="Returns Books data",
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
     * @OA\Get(
     *      path="/manager/books/list",
     *      operationId="listBooksManager",
     *      tags={"API Endpoints for Manager"},
     *      summary="List Books",
     *      description="Returns Books data",
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
     * @OA\Get(
     *      path="/books/find",
     *      operationId="FindBook",
     *      tags={"API Endpoints for Admin"},
     *      summary="Find a Book",
     *      description="Returns Books data",
     *      security={{"bearerAuth":{}}},
     *     @OA\Parameter(
    *         name="id",
    *         in="query",
    *         required=true,
    *         description="ID Book",
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
     * @OA\Get(
     *      path="/manager/books/find",
     *      operationId="FindBookManager",
     *      tags={"API Endpoints for Manager"},
     *      summary="Find a Book",
     *      description="Returns Books data",
     *      security={{"bearerAuth":{}}},
     *     @OA\Parameter(
    *         name="id",
    *         in="query",
    *         required=true,
    *         description="ID Book",
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

    public function runValidationShow($request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:books,id'
        ]);

        return $validator;
    }

    /**
    * @OA\Post(
    *      path="/books/create",
    *      operationId="createBook",
    *      tags={"API Endpoints for Admin"},
    *      summary="Create a new Book",
    *      description="Returns Book data",
     *      security={{"bearerAuth":{}}},
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *              required={"title", "quantity", "category_id"},
    *              @OA\Property(property="title", type="string", example="John Doe"),
    *              @OA\Property(property="quantity", type="integer", example=10),
    *              @OA\Property(property="category_id", type="integer", example=2),
    *             
    *          )
    *      ),
    *       @OA\Response(
    *          response=200,
    *          description="Book created successfully",
    *          @OA\JsonContent(ref="#/components/schemas/ApiResponse")
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad request",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorResponse")
    *      )
    * )
    *
    * @OA\Post(
    *      path="/manager/books/create",
    *      operationId="createBookManager",
    *      tags={"API Endpoints for Manager"},
    *      summary="Create a new Book",
    *      description="Returns Book data",
     *      security={{"bearerAuth":{}}},
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *              required={"title", "quantity", "category_id"},
    *              @OA\Property(property="title", type="string", example="John Doe"),
    *              @OA\Property(property="quantity", type="integer", example=10),
    *              @OA\Property(property="category_id", type="integer", example=2),
    *             
    *          )
    *      ),
    *       @OA\Response(
    *          response=200,
    *          description="Book created successfully",
    *          @OA\JsonContent(ref="#/components/schemas/ApiResponse")
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad request",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorResponse")
    *      )
    * )
    */

    public function runValidationCreate($request)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'required|string|max:50',
            'quantity' => 'required|integer',
            'category_id' => 'required|integer',

        ]);

        return $validator;
    }

    /**
    * @OA\Put(
    *      path="/books/update",
    *      operationId="updateBook",
    *      tags={"API Endpoints for Admin"},
    *      summary="Update a Book",
    *      description="Returns Book data",
     *      security={{"bearerAuth":{}}},
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *              required={"id", "title", "quantity", "category_id"},
    *              @OA\Property(property="id", type="integer", example=9),
    *              @OA\Property(property="title", type="string", example="Programmer Updated"),
    *              @OA\Property(property="quantity", type="integer", example=10),
    *              @OA\Property(property="category_id", type="integer", example=2),
    *             
    *          )
    *      ),
    *       @OA\Response(
    *          response=200,
    *          description="Book updated successfully",
    *          @OA\JsonContent(ref="#/components/schemas/ApiResponse")
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad request",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorResponse")
    *      )
    * )
    *
    * @OA\Put(
    *      path="/manager/books/update",
    *      operationId="updateBookManager",
    *      tags={"API Endpoints for Manager"},
    *      summary="Update a Book",
    *      description="Returns Book data",
     *      security={{"bearerAuth":{}}},
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *              required={"id", "title", "quantity", "category_id"},
    *              @OA\Property(property="id", type="integer", example=9),
    *              @OA\Property(property="title", type="string", example="Programmer Updated"),
    *              @OA\Property(property="quantity", type="integer", example=10),
    *              @OA\Property(property="category_id", type="integer", example=2),
    *             
    *          )
    *      ),
    *       @OA\Response(
    *          response=200,
    *          description="Book updated successfully",
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
            'id'  => 'required|exists:books,id',
            'title'  => 'required|string|max:250',
            'quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',

        ]);

        return $validator;
    }

    /**
     * @OA\Delete(
     *      path="/books/delete",
     *      operationId="deleteBook",
     *      tags={"API Endpoints for Admin"},
     *      summary="Delete a Book",
     *      security={{"bearerAuth":{}}},
     *     @OA\Parameter(
    *         name="id",
    *         in="query",
    *         required=true,
    *         description="ID Book",
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
     * @OA\Delete(
     *      path="/manager/books/delete",
     *      operationId="deleteBookManager",
     *      tags={"API Endpoints for Manager"},
     *      summary="Delete a Book",
     *      security={{"bearerAuth":{}}},
     *     @OA\Parameter(
    *         name="id",
    *         in="query",
    *         required=true,
    *         description="ID Book",
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
            'id' => 'required|exists:books,id,deleted_at,NULL'
        ]);

        return $validator;
    }
}
