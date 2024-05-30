<?php

namespace App\Http\Controllers\Book;

use Illuminate\Http\Request;
use App\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Validator;
use App\Services\CategoryServices;

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

    public function runValidationShow($request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:categories,id'
        ]);

        return $validator;
    }


    public function runValidationCreate($request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|unique:categories,name|max:50',
        ]);

        return $validator;
    }

    public function runValidationUpdate($request)
    {
        $validator = Validator::make($request->all(), [
            'id'  => 'required|unique:categories,id,'.$request->id.',id',
            'name'   => 'required|string|unique:categories,name|max:50',

        ]);

        return $validator;
    }

    public function runValidationDestroy($request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:categories,id,deleted_at,NULL'
        ]);

        return $validator;
    }
}
