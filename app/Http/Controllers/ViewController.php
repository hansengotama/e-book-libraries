<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewController extends Controller
{
    protected $classroomController;
    protected $bookTypeController;
    protected $userController;
    protected $bookController;

    public function __construct(
        ClassRoomController $classroomController,
        BookTypeController $bookTypeController,
        UserController $userController,
        BookController $bookController
    ) {
        $this->classroomController = $classroomController;
        $this->bookTypeController = $bookTypeController;
        $this->userController = $userController;
        $this->bookController = $bookController;
    }

    public function home(Request $request) {
        $userId = $request->get('user_id') == "null" ? null : $request->get('user_id');
        $classId = $request->get('class_id') == "null" ? null : $request->get('class_id');

        if($userId != null) {
            $user = $this->userController->find($userId);

            if($classId != null && $user->class_room_id != $classId) {
                $userId = null;
            }
        }

        $classrooms = $this->classroomController->getAll();
        $bookTypes = $this->bookTypeController->getAll();

        $bookTypeId = $request->get('book_type_id') == "null" ? null : $request->get('book_type_id');

        $title = $request->get('title');
        $users = $this->userController->getAll($classId, "user");
        $books = $this->bookController->getAll($bookTypeId, $classId, $userId, true, $title);

        return view('home', compact('classrooms', 'bookTypes', 'users', 'books', 'bookTypeId', 'classId', 'userId', 'title'));
    }

    public function login() {
        return view('login');
    }

    public function manageUser() {
        return view('admin.user');
    }

    public function manageBookType() {
        return view('admin.book-type');
    }

    public function downloadFile($url) {
        dd(1);
        dd($url);
    }
}
