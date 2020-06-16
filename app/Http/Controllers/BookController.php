<?php

namespace App\Http\Controllers;

use App\Book;
use App\BookComment;
use App\BookRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    protected $bookTypeController;

    public function __construct(
        BookTypeController $bookTypeController
    ) {
        $this->bookTypeController = $bookTypeController;
    }

    public function manageMyBook(Request $request)
    {
        $userId = Auth::user()->id;

        $title = $request->get("title");

        $books = Book::where('user_id', $userId)->with(["bookType", "user" => function($u) {
            $u->with("classRoom");
        }]);

        if($title != null)
            $books->where('title', 'LIKE', '%'. $title . '%');

        $bookData = $books->get();

        foreach ($bookData as $bookDatum) {
            $bookDatum['rate'] = $this->calculateBookRating($bookDatum['id']);
        }

        return view('user.my-book', compact('bookData', 'title'));
    }

    public function calculateBookRating($book_id) {
        $bookRatings = BookRating::where("book_id", $book_id)->get();
        $totalRate = 0;
        foreach ($bookRatings as $bookRating) {
            $totalRate += $bookRating['rate'];
        }

        if(count($bookRatings) > 0) return round($totalRate / count($bookRatings), 1);

        return null;
    }

    public function getAll(?int $bookTypeId, ?int $classId, ?int $userId, bool $isPublic, ?string $title)
    {
        $books = Book::where("is_public", $isPublic);

        if($bookTypeId != null)
            $books->where("book_type_id", $bookTypeId);

        if($classId != null)
            $books->with(["user" => function($u) use ($classId) {
                $u->where("class_room_id", $classId);
            }]);

        if($userId != null)
            $books->where("user_id", $userId);

        if($title != null)
            $books->where('title', 'LIKE', '%'. $title . '%');

        $bookData = $books->get();

        foreach ($bookData as $key => $bookDatum) {
             if($bookDatum['user'] == null) {
                 unset($bookData[$key]);
             }else {
                 $bookDatum['rate'] = $this->calculateBookRating($bookDatum['id']);
             }
        }

        return $bookData;
    }

    public function addMyBook()
    {
        $bookTypes = $this->bookTypeController->getAll();

        return view('user.add-my-book', compact('bookTypes'));
    }

    public function editMyBook($id)
    {
        $userId = Auth::user()->id;
        $book = $this->find($id);

        if($book == null) abort(404);
        if($userId != $book['user_id']) abort(401);

        $bookTypes = $this->bookTypeController->getAll();
        return view('user.edit-my-book', compact('book', 'bookTypes'));
    }

    public function addMyBookAction(Request $request)
    {
        $request->validate([
            "book_type_id" => 'required',
            "title" => 'required',
            "description" => 'required',
            "cover" => 'required',
            "file" => 'required',
            "is_public" => 'required',
        ]);

        $isPublic = $request['is_public'] == "true";

        $user = Auth::user();

        $path = "book/";
        $cover = $request->file('cover');
        $file = $request->file('file');

        $coverName = $user['name']."-".$user['id']."-"."cover"."-".$cover->getClientOriginalName();
        $cover->storeAs("public/".$path , $coverName);
        $coverPath = $path.$coverName;

        $fileName = $user['name']."-".$user['id']."-"."file"."-".$file->getClientOriginalName();
        $file->storeAs("public/".$path , $fileName);
        $filePath = $path.$fileName;

        Book::create([
            'user_id' => $user->id,
            'book_type_id' => $request['book_type_id'],
            'title' => $request['title'],
            'description' => $request['description'],
            'cover_url' => $coverPath,
            'file_url' => $filePath,
            'is_public' => $isPublic,
        ]);

        return redirect()->route("manage-my-book");
    }

    public function editMyBookAction(Request $request, $id)
    {
        $request->validate([
            "book_type_id" => 'required',
            "title" => 'required',
            "description" => 'required',
            "is_public" => 'required',
        ]);

        $isPublic = $request['is_public'] == "true";
        $user = Auth::user();
        $book = $this->find($id);

        $path = "book/";
        $coverPath = $book["cover_url"];
        $filePath = $book["file_url"];
        $cover = $request->file('cover');
        $file = $request->file('file');

        if($cover != null) {
            if(is_file($coverPath)) Storage::delete($coverPath);

            $coverName = $user['name']."-".$user['id']."-"."cover"."-".$cover->getClientOriginalName();
            $cover->storeAs("public/".$path , $coverName);
            $coverPath = $path.$coverName;
        }

        if($file != null) {
            if(is_file($filePath)) Storage::delete($filePath);

            $fileName = $user['name']."-".$user['id']."-"."file"."-".$file->getClientOriginalName();
            $file->storeAs("public/".$path , $fileName);
            $filePath = $path.$fileName;
        }

        $book->update([
            'user_id' => $user->id,
            'book_type_id' => $request['book_type_id'],
            'title' => $request['title'],
            'description' => $request['description'],
            'cover_url' => $coverPath,
            'file_url' => $filePath,
            'is_public' => $isPublic,
        ]);

        return redirect()->route("manage-my-book");
    }

    public function deleteMyBook($id)
    {
        $book = $this->find($id);
        $userId = Auth::user()->id;

        if($book == null) abort(404);
        if($userId != $book['user_id']) abort(401);

        $book->delete();

        return redirect()->back();
    }

    public function deleteBook($id)
    {
        if(Auth::user()->role != "admin") abort(401);

        $book = $this->find($id);

        if($book == null) abort(404);
        $book->delete();

        return redirect()->back();
    }

    public function setBookPrivate($id) {
        if(Auth::user()->role != "admin") abort(401);

        $book = $this->find($id);

        $book->update([
            'user_id' => $book['user_id'],
            'book_type_id' => $book['book_type_id'],
            'title' => $book['title'],
            'description' => $book['description'],
            'cover_url' => $book['cover_url'],
            'file_url' => $book['file_url'],
            'is_public' => false,
        ]);

        return redirect()->back();
    }

    public function get()
    {
        return Book::get();
    }

    public function find($id)
    {
        return Book::where('id', $id)->with(["bookComments" => function($bookComment) {
            $bookComment->with("user");
        }])->first();
    }

    public function bookDetail($book_id)
    {
        $userId = Auth::check() ? Auth::user()->id : null;
        $book = $this->find($book_id);

        if($book == null) abort(404);
        if($book['is_public'] == false && $userId != $book['user_id']) abort(401);

        $book['rate'] = $this->calculateBookRating($book['id']);
        $myRating = $this->findBookRating($book['id'], $userId);

        $book['my_rating'] = $myRating == null ? 0 : $myRating['rate'];

        return view('book-detail', compact('book'));
    }

    public function rateBook(Request $request) {
        $userId = Auth::user()->id;
        $bookId = $request->get('book_id');
        $rate = $request->get('rate');

        $bookRating = $this->findBookRating($bookId, $userId);
        if($bookRating == null) $this->createRateBook($bookId, $userId, $rate);
        else $this->updateRateBook($bookId, $userId, $rate);

        return redirect()->back();
    }

    public function findBookRating($bookId, $userId) {
        return BookRating::where("book_id", $bookId)->where("user_id", $userId)->first();
    }

    public function updateRateBook($bookId, $userId, $rate) {
        $bookRating = $this->findBookRating($bookId, $userId);

        $bookRating->update([
            "book_id" => $bookId,
            "user_id" => $userId,
            "rate" => $rate,
        ]);
    }

    public function createRateBook($bookId, $userId, $rate) {
        return BookRating::create([
            "book_id" => $bookId,
            "user_id" => $userId,
            "rate" => $rate,
        ]);
    }

    public function addBookComment(Request $request) {
        $comment = $request->get('comment');
        $bookId = $request->get('book_id');

        BookComment::create([
            "book_id" => $bookId,
            "user_id" => Auth::user()->id,
            "comment" => $comment,
        ]);

        return redirect()->back();
    }
}
