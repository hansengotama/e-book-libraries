@extends('layout.index')

@section('style')
    <style>
        #user {
            background: #eaeaea;
        }

        .user-container {
            padding: 40px 25px;
        }

        .user-container > .custom-container {
            background: white;
            height: 100%;
            padding: 20px 20px 100px 20px;
            border-radius: 15px;
        }

        .user-container > .custom-container > .title {
            font-weight: 500;
        }

        .user-container > .custom-container > .add-user {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-container > .custom-container > .add-user > button {
            margin-top: 8px;
            cursor: pointer;
            padding: 6px 16px;
            border-radius: 5px;
            border-color: #eaeaea;
            background: #f8c94e;
            color: white;
        }

        .user-container > .custom-container > .add-user > .search {
            display: flex;
            align-items: center;
        }

        .user-container > .custom-container > .add-user > .search > input {
            padding: 6px 8px;
            border: 1px solid black;
            border-right-color: white;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }

        .user-container > .custom-container > .add-user > .search > i {
            padding: 10px 8px;
            border: 1px solid black;
            border-left-color: white;
            cursor: pointer;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }

        .user-container > .custom-container > .add-user > .search > select {
            height: 38px;
            margin-right: 10px;
            width: 150px;
            text-indent: 5px;
        }

        .user-container > .custom-container > .table-container {
            margin-top: 25px;
        }

        .user-container > .custom-container > .table-container > table {
            width: 100%;
        }

        .form-container {
            margin-bottom: 30px;
        }
    </style>
@endsection

@section('content')
    <div id="user">
        <div class="user-container">
            <div class="custom-container">
                <div class="title">
                    USER
                </div>
                <hr>
                <div class="add-user">
                    <button onclick="addUser()">
                        <i class="fa fa-plus"></i> Add User
                    </button>
                    <div class="search">
                        <select onchange="search()" id="class_room_id">
                            <option value="all">All classroom</option>
                            @foreach($classrooms as $classroom)
                                <option value="{{ $classroom['id'] }}" {{ ($filterClassRoomId == $classroom['id'] ? "selected" : "") }}>
                                    {{ $classroom['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <input type="text" placeholder="search by name" id="search" value="{{ $filterName }}">
                        <i class="fa fa-search" onclick="search()"></i>
                    </div>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Action</th>
                        </tr>
                        <thead>
                        <tbody>
                        @if(count($users) <= 0)
                            <tr>
                                <td colspan="5" class="red">user not found</td>
                            </tr>
                        @else
                            @foreach($users as $key => $user)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $user['name'] }}</td>
                                    <td>{{ $user['classRoom']['name'] }}</td>
                                    <td>{{ $user['email'] }}</td>
                                    <td>{{ $user['gender'] }}</td>
                                    <td>
                                        <a class="edit" onclick="editUser({{$user}})">Edit</a>
                                        <a class="delete" onclick="deleteConfirmation({{ $user['id'] }})">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $users->links() }}
                </div>

            </div>
        </div>
        <div id="error" data-error="{{ $errors }}"></div>
    </div>
@endsection

@section('modal')
    <div class="modal fade" id="add-user" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <form method="POST" action="{{ route('create-user-action') }}">
                {!! csrf_field() !!}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-container">
                            <div class="input-container">
                                <div class="title">
                                    Classroom <span class="red">*</span>
                                </div>
                                <select name="class_room_id" required>
                                    @foreach($classrooms as $classroom)
                                        <option value="{{ $classroom['id'] }}" {{ (old("class_room_id") == $classroom['id'] ? "selected" : "") }}>
                                            {{ $classroom['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_room_id')
                                <small class="error red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-container">
                            <div class="input-container">
                                <div class="title">
                                    Name <span class="red">*</span>
                                </div>
                                <input type="text" placeholder="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                <small class="error red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-container">
                            <div class="input-container">
                                <div class="title">
                                    Email <span class="red">*</span>
                                </div>
                                <input type="email" placeholder="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                <small class="error red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-container">
                            <div class="input-container">
                                <div class="title">
                                    Gender <span class="red">*</span>
                                </div>
                                <select name="gender" required>
                                    <option value="male" {{ old("gender") == "male" ? "selected" : "" }}>male</option>
                                    <option value="female" {{ old("gender") == "female" ? "selected" : "" }}>female</option>
                                </select>
                                @error('gender')
                                <small class="error red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-container">
                            <div class="input-container">
                                <div class="title">
                                    Address <span class="red">*</span>
                                </div>
                                <input type="text" placeholder="address" name="address" value="{{ old("address") }}" required>
                                @error('address')
                                <small class="error red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-container">
                            <div class="input-container">
                                <div class="title">
                                    Date of Birth <span class="red">*</span>
                                </div>
                                <input type="date" name="date_of_birth" value="{{ old("date_of_birth") }}" required>
                                @error('date_of_birth')
                                <small class="error red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-container">
                            <div class="input-container">
                                <div class="title">
                                    Password
                                </div>
                                <input type="text" name="password" value="password" required readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn bg-yellow white" >Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="edit-user" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <form method="POST" action="" id="edit-form">
                {!! csrf_field() !!}

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="edit-id" name="id" value="{{ old("id") }}" hidden>
                        <div class="form-container">
                            <div class="input-container">
                                <div class="title">
                                    Classroom <span class="red">*</span>
                                </div>
                                <select name="edit_class_room_id" id="edit-class-room-id" required>
                                    @foreach($classrooms as $classroom)
                                        <option value="{{ $classroom['id'] }}" {{ (old("edit_class_room_id") == $classroom['id'] ? "selected" : "") }}>
                                            {{ $classroom['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('edit_class_room_id')
                                <small class="error red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-container">
                            <div class="input-container">
                                <div class="title">
                                    Name <span class="red">*</span>
                                </div>
                                <input type="text" placeholder="name" id="edit-name" name="edit_name" value="{{ old('edit_name') }}" required>
                                @error('edit_name')
                                <small class="error red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-container">
                            <div class="input-container">
                                <div class="title">
                                    Email <span class="red">*</span>
                                </div>
                                <input type="email" placeholder="email" id="edit-email" name="edit_email" value="{{ old('edit_email') }}" required>
                                @error('edit_email')
                                <small class="error red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-container">
                            <div class="input-container">
                                <div class="title">
                                    Gender <span class="red">*</span>
                                </div>
                                <select name="edit_gender" id="edit-gender" required>
                                    <option value="male" {{ old("edit_gender") == "male" ? "selected" : "" }}>male</option>
                                    <option value="female" {{ old("edit_gender") == "female" ? "selected" : "" }}>female</option>
                                </select>
                                @error('edit_gender')
                                <small class="error red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-container">
                            <div class="input-container">
                                <div class="title">
                                    Address <span class="red">*</span>
                                </div>
                                <input type="text" placeholder="address" id="edit-address" name="edit_address" value="{{ old("edit_address") }}" required>
                                @error('edit_address')
                                <small class="error red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-container">
                            <div class="input-container">
                                <div class="title">
                                    Date of Birth <span class="red">*</span>
                                </div>
                                <input type="date" name="edit_date_of_birth" id="edit-date-of-birth" value="{{ old("edit_date_of_birth") }}" required>
                                @error('edit_date_of_birth')
                                <small class="error red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-container">
                            <div class="input-container">
                                <div class="title">
                                    Password
                                </div>
                                <input type="text" name="edit_password" value="password" required readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn bg-yellow white" >Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        let selectedUser = null

        $(() => {
            checkHasError()
            triggerSearch()
        })

        function triggerSearch() {
            let search = $("#search")
            if(search != "") {
                let nameLength = search.val().length;
                $("#search").focus()

                search[0].setSelectionRange(nameLength, nameLength)
            }

            $("#search").keyup((e) => {
                if(e.keyCode === 13) {
                    this.search()
                }
            })
        }

        function addUser() {
            let classrooms = {!! json_encode($classrooms->toArray()) !!}
            if(classrooms.length <= 0) {
                return swal("Error!", "Please add classroom first!", "error");
            }

            selectedUser = null
            showAddForm()
        }

        function showAddForm() {
            $('#add-user').modal('show')
        }

        function checkHasError() {
            let error = $("#error").data('error')
            let firstObjectKey = Object.keys(error)[0]
            if(firstObjectKey != undefined) {
                let isEdit = firstObjectKey.split("_")

                if(isEdit[0] == "edit") showEditForm()
                else showAddForm()
            }
        }

        function editUser(user) {
            selectedUser = user

            $("#edit-name").val(user.name)
            $("#edit-class-room-id").val(user.class_room_id)
            $("#edit-email").val(user.email)
            $("#edit-gender").val(user.gender)
            $("#edit-address").val(user.address)
            $("#edit-date-of-birth").val(user.date_of_birth)
            $("#edit-id").val(user.id)

            showEditForm()
        }

        function showEditForm() {
            let userId;
            $("#edit-user").modal('show')

            if(selectedUser == null)
                userId = $("#edit-id").val()
            else
                userId = selectedUser.id

            $("#edit-form").attr('action', '/user/update/'+userId);
        }

        function deleteConfirmation(id) {
            swal({
                title: "Are you sure?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    location.href = "/user/delete/" + id
                }
            });
        }

        function search() {
            let search = $("#search").val()
            let class_room_id = $("#class_room_id").children("option:selected").val()

            location.href = "?name="+search+"&class_room_id="+class_room_id
        }
    </script>
@endsection
