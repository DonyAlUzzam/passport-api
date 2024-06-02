<?php

namespace App\Services;

use App\Models\Book;
use Exception;

class BookServices
{
    public function __construct()
    {
        // Carbon::setLocale('id');
    }

    public function getDatatable($request, $meta)
    {
        $query = Book::query();
        if ($request->search !== null) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $query = $query->with('category')->orderBy('created_at', $meta['orderBy']);

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
        $book = Book::with('category')->find($id);
        return $book;
    }

    public function store($request)
    {

        $newBook = new Book();
        $newBook = $this->setContent($newBook, $request);
        $newBook->save();
        $newBook = Book::with('category')->find($newBook->id);

        return $newBook;
    }

    public function update($id, $request)
    {
        $book = $this->getDetailByID($id);
        $book = $this->setContent($book, $request);
        $book->update();
        $book = Book::with('category')->find($book->id);

        return $book;
    }

    public function delete($id)
    {
        $book = $this->getDetailByID($id);
        $book->delete();

        return $book;
    }

    public function setContent($data, $request){
        $data->title = $request->title;
        $data->quantity = $request->quantity;
        $data->category_id = $request->category_id;

        return $data;

    }

}
