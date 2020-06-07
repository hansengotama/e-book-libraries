@extends('layout.index')

@section('style')
    <style>
        #classroom {
            background: #eaeaea;
        }

        .classroom-container {
            padding: 40px 25px;
        }

        .classroom-container > .custom-container {
            background: white;
            height: 100%;
            padding: 20px 20px 100px 20px;
            border-radius: 15px;
        }

        .classroom-container > .custom-container > .title {
            font-weight: 500;
        }

        .classroom-container > .custom-container > .add-classroom {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .classroom-container > .custom-container > .add-classroom > button {
            margin-top: 8px;
            cursor: pointer;
            padding: 6px 16px;
            border-radius: 5px;
            border-color: #eaeaea;
            background: #f8c94e;
            color: white;
        }

        .classroom-container > .custom-container > .add-classroom > .search {
            display: flex;
            align-items: center;
        }

        .classroom-container > .custom-container > .add-classroom > .search > input {
            padding: 6px 8px;
            border: 1px solid black;
            border-right-color: white;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }

        .classroom-container > .custom-container > .add-classroom > .search > i {
            padding: 10px 8px;
            border: 1px solid black;
            border-left-color: white;
            cursor: pointer;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }

        .classroom-container > .custom-container > .table-container {
            margin-top: 25px;
        }

        .classroom-container > .custom-container > .table-container > table {
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div id="classroom">
        <div class="classroom-container">
            <div class="custom-container">
                <div class="title">
                    CLASSROOM
                </div>
                <hr>
                <div class="add-classroom">
                    <button onclick="addClassroom()">
                        <i class="fa fa-plus"></i> Add Classroom
                    </button>
                    <div class="search">
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
                                <th>Action</th>
                            </tr>
                        <thead>
                        <tbody>
                            @if(count($classrooms) <= 0)
                            <tr>
                                <td colspan="3" class="red">classroom not found</td>
                            </tr>
                            @else
                            @foreach($classrooms as $key => $classroom)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $classroom['name'] }}</td>
                                <td>
                                    <a class="edit" onclick="editClassroom({{$classroom}})">Edit</a>
                                    <a class="delete" onclick="deleteConfirmation({{ $classroom['id'] }})">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $classrooms->links() }}
                </div>

            </div>
        </div>
        @error('name')
            <div id="error" data-error="{{ $message }}"></div>
        @enderror
        @error('edit-name')
            <div id="edit-error" data-error="{{ $message }}"></div>
        @enderror
    </div>
@endsection

@section('modal')
    <div class="modal fade" id="add-classroom" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <form method="POST" action="{{ route('create-classroom-action') }}">
                {!! csrf_field() !!}

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Classroom</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-container">
                            <div class="input-container">
                                <div class="title">
                                    Name <span class="red">*</span>
                                </div>
                                <input type="text" placeholder="name" name="name" required>
                                @error('name')
                                    <small class="error red">{{ $message }}</small>
                                @enderror
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

    <div class="modal fade" id="edit-classroom" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <form method="POST" action="" id="edit-form">
                {!! csrf_field() !!}

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Edit Classroom</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="edit-id" name="id" value="{{ old("id") }}" hidden>
                        <div class="form-container">
                            <div class="input-container">
                                <div class="title">
                                    Name <span class="red">*</span>
                                </div>
                                <input type="text" placeholder="name" name="edit-name" id="edit-name" value="{{ old("edit-name") }}" required>
                                @error('edit-name')
                                    <small class="error red">{{ $message }}</small>
                                @enderror
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
        let selectedClassRoom = null

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

        function addClassroom() {
            selectedClassRoom = null
            showAddForm()
        }

        function showAddForm() {
            $('#add-classroom').modal('show')
        }

        function checkHasError() {
            let error = $("#error").data('error')
            let editError = $("#edit-error").data('error')
            if(error != undefined) {
                showAddForm()
            }

            if(editError != undefined) {
                showEditForm()
            }
        }

        function editClassroom(classroom) {
            selectedClassRoom = classroom

            $("#edit-name").val(classroom.name)
            $("#edit-id").val(classroom.id)

            showEditForm()
        }

        function showEditForm() {
            let classroomId;
            $("#edit-classroom").modal('show')

            if(selectedClassRoom == null)
                classroomId = $("#edit-id").val()
            else
                classroomId = selectedClassRoom.id

            $("#edit-form").attr('action', '/classroom/update/'+classroomId);
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
                    location.href = "/classroom/delete/" + id
                }
            });
        }

        function search() {
            let search = $("#search").val()

            location.href = "?name="+search
        }
    </script>
@endsection
