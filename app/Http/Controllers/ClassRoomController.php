<?php

namespace App\Http\Controllers;

use App\ClassRoom;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassRoomController extends Controller
{
    public function manageClassroom(Request $request) {
        $filterName = $request['name'];
        $classrooms = $this->get($filterName);

        return view('admin.classroom', compact('classrooms', 'filterName'));
    }

    public function getAll()
    {
        return ClassRoom::get();
    }

    public function get($name = null)
    {
        $classroom = ClassRoom::query();

        if($name != null)
            $classroom = $classroom->where('name', 'LIKE', '%'. $name . '%');

        return $classroom->paginate(10);
    }

    public function create(Request $request)
    {
        $request->validate([
            "name" => 'required',
        ]);

        ClassRoom::create([
            'name' => $request['name'],
        ]);

        return redirect()->back();
    }

    public function find($id)
    {
        return ClassRoom::where('id', $id)->first();
    }

    public function update(Request $request, $id)
    {
        if($request['edit-name'] == "" || $request['edit-name'] == null) {
            return redirect()->back()->withInput($request->only('edit-name', 'id'))->withErrors([
                'edit-name' => 'Name must be required.',
            ]);
        }

        $classRoom = $this->find($id);

        $classRoom->update([
            'name' => $request['edit-name'],
        ]);

        return redirect()->back();
    }

    public function delete($id)
    {
        $classRoom = $this->find($id);

        $classRoom->delete();

        return redirect()->back();
    }

    public function viewMyClass()
    {
        $classRoomId = Auth::user()->class_room_id;

        $classRoomUser = User::where('class_room_id', $classRoomId)->get();
        $class = ClassRoom::where('id', $classRoomId)->first();

        return view('user.my-class', compact('classRoomUser', 'class'));
    }

    public function viewClassById($id)
    {
        $classroom = $this->find($id);
        $classRoomUser = User::where('class_room_id', $id)->get();

        return view('admin.class-student', compact('classRoomUser', 'classroom'));
    }
}
