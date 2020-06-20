<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $classroomController;

    public function __construct(ClassRoomController $classroomController)
    {
        $this->classroomController = $classroomController;
    }

    public function manageUser(Request $request) {
        $filterName = $request['name'];
        $filterClassRoomId = $request['class_room_id'];

        $users = $this->get("user", $filterClassRoomId, $filterName);
        $classrooms = $this->classroomController->getAll();

        return view('admin.user',
            compact(
                'users',
                'filterName',
                'filterClassRoomId',
                'classrooms'
            )
        );
    }

    public function getAll(?int $classId, ?string $role)
    {
        $users = User::query();
        if($classId != null)
            $users->where("class_room_id", $classId);

        if($role != null)
            $users->where("role", $role);

        return $users->get();
    }

    public function get($role = null, $class_room_id = null, $name = null)
    {
        $user = User::query();

        if($role != null)
            $user = $user->where('role', $role);

        if($class_room_id != null && $class_room_id != "all")
            $user = $user->where('class_room_id', $class_room_id);

        if($name != null)
            $user = $user->where('name', 'LIKE', '%'. $name . '%');

        return $user->with("classRoom")->paginate(10);
    }

    public function create(Request $request)
    {
        $request->validate([
            'class_room_id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'date_of_birth' => 'required',
        ]);

        $role = "user";
        if($request['role'] == "admin") $role = "admin";

        User::create([
            'class_room_id' => $request['class_room_id'],
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'role' => $role,
            'gender' => $request['gender'],
            'address' => $request['address'],
            'image_url' => $request['image_url'],
            'date_of_birth' => $request['date_of_birth'],
        ]);

        return redirect()->back();
    }

    public function find(int $id)
    {
        return User::where('id', $id)->first();
    }

    public function update(Request $request, $id)
    {
        $errors = [];
        if($request['edit_class_room_id'] == "" || $request['edit_class_room_id'] == null) {
            $errors['edit_class_room_id'] = 'Class room must be required.';
        }

        if($request['edit_name'] == "" || $request['edit_name'] == null) {
            $errors['edit_name'] = 'Name must be required.';
        }

        if($request['edit_email'] == "" || $request['edit_email'] == null) {
            $errors['edit_email'] = 'Email must be required.';
        }

        if($request['edit_gender'] == "" || $request['edit_gender'] == null) {
            $errors['edit_gender'] = 'Gender must be required.';
        }

        if($request['edit_address'] == "" || $request['edit_address'] == null) {
            $errors['edit_address'] = 'Address must be required.';
        }

        if($request['edit_date_of_birth'] == "" || $request['edit_date_of_birth'] == null) {
            $errors['edit_date_of_birth'] = 'Date of birth must be required.';
        }

        if(count($errors) > 0) {
            return redirect()->back()->withInput($request->all())->withErrors($errors);
        }

        $role = "user";
        if($request['role'] == "admin") $role = "admin";

        $user = $this->find($id);

        $user->update([
            'class_room_id' => $request['edit_class_room_id'],
            'name' => $request['edit_name'],
            'email' => $request['edit_email'],
            'password' => $user['password'],
            'role' => $role,
            'gender' => $request['edit_gender'],
            'address' => $request['edit_address'],
            'image_url' => $user['image_url'],
            'date_of_birth' => $request['edit_date_of_birth'],
        ]);

        return redirect()->back();
    }

    public function delete($id)
    {
        $user = $this->find($id);

        $user->delete();

        return redirect()->back();
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required | email',
            'password' => 'required',
        ]);

        $checkUser = Auth::attempt([
            'email' => $request['email'],
            'password' => $request['password'],
        ]);


        if($checkUser)
            return \redirect()->to('/');

        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors([
            'validate' => 'Invalid Email and Password',
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return \redirect()->route('login');
    }

    public function manageProfile(Request $request)
    {
        $user = Auth::user();

        return view('profile', compact('user'));
    }

    public function editProfile(Request $request)
    {
        $user = User::where("id", Auth::user()->id)->first();

        $imageUrl = $request->file('image_url');
        $filePath = $user['image_url'];
        if($imageUrl != null) {
            $path = "profile-photo/";
            $filename = $user['name']."-".$user['id']."-".$imageUrl->getClientOriginalName();
            $imageUrl->storeAs("public/".$path , $filename);

            $filePath = $path.$filename;
        }

        $user->update([
            'class_room_id' => $user['class_room_id'],
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $user['password'],
            'role' => $user['role'],
            'gender' => $request['gender'],
            'address' => $request['address'],
            'image_url' => $filePath,
            'date_of_birth' => $request['date_of_birth'],
        ]);

        return redirect()->back();
    }

    public function checkPassword()
    {
        $user = User::where("id", Auth::user()->id)->first();

        $hasher = app('hash');
        if ($hasher->check('passwordToCheck', $user->password)) {
            return true;
        }

        return false;
    }

    public function editPassword(Request $request) {
        $user = User::where("id", Auth::user()->id)->first();

        $user->password = Hash::make($request["password"]);
        $user->save();

        return redirect()->back();
    }
}
