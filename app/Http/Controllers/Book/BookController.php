<?php

namespace App\Http\Controllers\Book;

use Illuminate\Http\Request;
use App\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Validator;
use App\Services\BookServices;

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

    public function runValidationShow($request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:books,id'
        ]);

        return $validator;
    }


    public function runValidationCreate($request)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'required|string|max:50',
            'quantity' => 'required|integer',
            'category_id' => 'required|integer',

        ]);

        return $validator;
    }

    public function runValidationUpdate($request)
    {
        $validator = Validator::make($request->all(), [
            'id'  => 'required|unique:books,id,'.$request->id.',id',
            'title'  => 'required|string|max:250',
            'quantity' => 'required|integer',
            'category_id' => 'required|integer',

        ]);

        return $validator;
    }

    public function runValidationDestroy($request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:books,id'
        ]);

        return $validator;
    }
}
