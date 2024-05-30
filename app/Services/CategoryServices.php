<?php

namespace App\Services;

use App\Models\Category;
use Exception;

class CategoryServices
{
    public function __construct()
    {
        // Carbon::setLocale('id');
    }

    public function getDatatable($request, $meta)
    {
        $query = Category::query();
        if ($request->search !== null) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $query = $query->orderBy('created_at', $meta['orderBy']);

        $data = $query->paginate($meta['limit']);
        $meta = [
            'total'        => $data->total(),
            'count'        => $data->count(),
            'per_page'     => $data->perPage(),
            'current_page' => $data->currentPage(),
            'total_pages'  => $data->lastPage()
        ];

        return ['data' => $data->toArray()['data'], 'meta' => $meta];
    }

    public function getDetailByID($id)
    {
        $category = Category::find($id);
        return $category;
    }

    public function store($request)
    {

        $newCategory = new Category();
        $newCategory = $this->setContent($newCategory, $request);
        $newCategory->save();

        return $newCategory;
    }

    public function update($id, $request)
    {
        $category = $this->getDetailByID($id);
        $category = $this->setContent($category, $request);
        $category->update();

        return $category;
    }

    public function delete($id)
    {
        $category = $this->getDetailByID($id);

        $category->delete();
        $category->save();

        return $category;
    }

    public function setContent($data, $request){
        $data->name = $request->name;

        return $data;

    }



}
