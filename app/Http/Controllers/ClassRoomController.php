<?php

namespace App\Http\Controllers;

use App\ClassRoom;
use Illuminate\Http\Request;

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
}
