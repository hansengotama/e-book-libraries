@extends('layout.index')

@section('style')
    <style>
        #book-type {
            background: #eaeaea;
        }

        .book-type-container {
            padding: 40px 25px;
        }

        .book-type-container > .custom-container {
            background: white;
            height: 100%;
            padding: 20px 20px 100px 20px;
            border-radius: 15px;
        }

        .book-type-container > .custom-container > .title {
            font-weight: 500;
        }

        .book-type-container > .custom-container > .add-book-type {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .book-type-container > .custom-container > .add-book-type > button {
            margin-top: 8px;
            cursor: pointer;
            padding: 6px 16px;
            border-radius: 5px;
            border-color: #eaeaea;
            background: #f8c94e;
            color: white;
        }

        .book-type-container > .custom-container > .add-book-type > .search {
            display: flex;
            align-items: center;
        }

        .book-type-container > .custom-container > .add-book-type > .search > input {
            padding: 6px 8px;
            border: 1px solid black;
            border-right-color: white;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }

        .book-type-container > .custom-container > .add-book-type > .search > i {
            padding: 10px 8px;
            border: 1px solid black;
            border-left-color: white;
            cursor: pointer;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }

        .book-type-container > .custom-container > .table-container {
            margin-top: 25px;
        }

        .book-type-container > .custom-container > .table-container > table {
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div id="book-type">
        <div class="book-type-container">
            <div class="custom-container">
                <div class="title">
                    BOOK TYPE
                </div>
                <hr>
                <div class="add-book-type">
                    <button onclick="addBookType()">
                        <i class="fa fa-plus"></i> Add Book Type
                    </button>
                    <div class="search">
                        <input type="text" placeholder="search by name" id="search" value="{{ $filterName }}" >
                        <i class="fa fa-search" onclick="search()"></i>
                    </div>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Total Posted</th>
                            <th>Action</th>
                        </tr>
                        <thead>
                        <tbody>
                        @if(count($bookTypes) <= 0)
                            <tr>
                                <td colspan="3" class="red">book type not found</td>
                            </tr>
                        @else
                            @foreach($bookTypes as $key => $bookType)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $bookType['name'] }}</td>
                                    <td>{{ $bookType['count'] }}</td>
                                    <td>
                                        <a class="edit" onclick="editClassroom({{ $bookType }})">Edit</a>
                                        <a class="delete" onclick="deleteConfirmation({{ $bookType['id'] }})">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $bookTypes->links() }}
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
    <div class="modal fade" id="add-book-type" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <form method="POST" action="{{ route('create-book-type-action') }}">
                {!! csrf_field() !!}

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Book Type</h5>
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

    <div class="modal fade" id="edit-book-type" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <form method="POST" action="" id="edit-form">
                {!! csrf_field() !!}

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Edit Book Type</h5>
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
        let selectedBookType = null

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

        function addBookType() {
            selectedBookType = null
            showAddForm()
        }

        function showAddForm() {
            $('#add-book-type').modal('show')
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

        function editClassroom(bookType) {
            selectedBookType = bookType

            $("#edit-name").val(bookType.name)
            $("#edit-id").val(bookType.id)

            showEditForm()
        }

        function showEditForm() {
            let bookTypeId;
            $("#edit-book-type").modal('show')

            if(selectedBookType == null)
                bookTypeId = $("#edit-id").val()
            else
                bookTypeId = selectedBookType.id

            $("#edit-form").attr('action', '/book-type/update/'+bookTypeId);
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
                    location.href = "/book-type/delete/" + id
                }
            });
        }

        function search() {
            let search = $("#search").val()

            location.href = "?name="+search
        }
    </script>
@endsection
