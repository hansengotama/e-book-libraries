<?php

namespace App\Http\Controllers;

use App\Book;
use App\BookType;
use Illuminate\Http\Request;

class BookTypeController extends Controller
{
    public function countBookFromBookTypeId(int $bookTypeId) {
        return Book::where("book_type_id", $bookTypeId)->count();
    }

    public function manageBookType(Request $request) {
        $filterName = $request['name'];
        $bookTypes = $this->get($filterName);
        for($i = 0; $i < $bookTypes->count(); $i++) {
            $bookTypes[$i]['count'] = $this->countBookFromBookTypeId($bookTypes[$i]['id']);
        }

        return view('admin.book-type', compact('bookTypes', 'filterName'));
    }

    public function getAll()
    {
        return BookType::get();
    }

    public function get($name = null)
    {
        $bookType = BookType::query();

        if($name != null)
            $bookType = $bookType->where('name', 'LIKE', '%'. $name . '%');

        return $bookType->paginate(10);
    }

    public function create(Request $request)
    {
        $request->validate([
            "name" => 'required',
        ]);

        BookType::create([
            'name' => $request['name'],
        ]);

        return redirect()->back();
    }

    public function find($id)
    {
        return BookType::where('id', $id)->first();
    }

    public function update(Request $request, $id)
    {
        if($request['edit-name'] == "" || $request['edit-name'] == null) {
            return redirect()->back()->withInput($request->only('edit-name', 'id'))->withErrors([
                'edit-name' => 'Name must be required.',
            ]);
        }

        $bookType = $this->find($id);

        $bookType->update([
            'name' => $request['edit-name'],
        ]);

        return redirect()->back();
    }

    public function delete($id)
    {
        $bookType = $this->find($id);

        $bookType->delete();

        return redirect()->back();
    }
}
