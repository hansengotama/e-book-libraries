@extends('layout.index')

@section('style')
    <style>
        #edit-my-book {
            background: #eaeaea;
        }

        .edit-my-book-container {
            padding: 40px 25px;
        }

        .edit-my-book-container > .form-container {
            background: white;
            height: 100%;
            padding: 20px 20px 100px 20px;
            border-radius: 15px;
        }

        .edit-my-book-container > .form-container > .header-container {
            display: flex;
            align-items: center;
        }

        .edit-my-book-container > .form-container > .header-container > .button-container > button {
            cursor: pointer;
            padding: 6px 16px;
            border-radius: 5px;
            border-color: #eaeaea;
            background: #f8c94e;
            color: white;
        }

        .edit-my-book-container > .form-container > form > .button-container > button {
            cursor: pointer;
            padding: 6px 16px;
            border-radius: 5px;
            border-color: #eaeaea;
            background: #f8c94e;
            color: white;
        }

        .edit-my-book-container > .form-container > form > .input-container {
            margin-bottom: 1em;
        }

        .edit-my-book-container > .form-container > form > .input-container > .title {
            margin-bottom: 8px;
        }

        .edit-my-book-container > .form-container > form > .input-container > input,
        .edit-my-book-container > .form-container > form > .input-container > textarea,
        .edit-my-book-container > .form-container > form > .input-container > select {
            width: 100%;
            padding: 6px 8px;
            margin-bottom: 5px;
            border: 1px solid;
            border-radius: 6px;
        }

        .edit-my-book-container > .form-container > .header-container > .title {
            font-weight: 500;
        }
    </style>
@endsection

@section('content')
    <div id="edit-my-book">
        <div class="edit-my-book-container">
            <div class="form-container">
                <div class="header-container">
                    <div class="title">
                        EDIT BOOK
                    </div>
                    <div class="button-container">
                        <button onclick="goToViewMyBook()">View My Book</button>
                    </div>
                </div>
                <hr>
                <form method="POST" action="{{ route('edit-my-book-action', ['id' => $book['id']]) }}" enctype="multipart/form-data">
                    {!! csrf_field() !!}

                    <div class="input-container">
                        <div class="title">
                            Type <span class="red">*</span>
                        </div>
                        <select name="book_type_id" required>
                            @foreach($bookTypes as $bookType)
                                <option value="{{ $bookType['id'] }}" {{ ($book['book_type_id'] == $bookType['id'] ? "selected" : "") }}>
                                    {{ $bookType['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('book_type_id')
                        <small class="error red">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="input-container">
                        <div class="title">
                            Title <span class="red">*</span>
                        </div>
                        <input type="text" placeholder="Title" name="title" value="{{ $book['title'] }}" required>
                        @error('title')
                        <small class="error red">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="input-container">
                        <div class="title">
                            Description <span class="red">*</span>
                        </div>
                        <textarea placeholder="Description" name="description" cols="30" rows="10" required>{{ $book['description'] }}</textarea>
                        @error('description')
                        <small class="error red">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="input-container">
                        <div class="title">
                            Cover
                        </div>
                        <input type="file" name="cover" accept="image/*">
                        @error('cover')
                        <small class="error red">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="input-container">
                        <div class="title">
                            File
                        </div>
                        <input type="file" name="file" accept="application/pdf, application/msword">
                        @error('cover')
                        <small class="error red">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="input-container">
                        <div class="title">
                            Privilege <span class="red">*</span>
                        </div>
                        <select name="is_public" required>
                            <option value=true @if($book['is_public'] == true || $book['is_public'] == "true") selected @endif>Public</option>
                            <option value=false @if($book['is_public'] == false || $book['is_public'] == "false") selected @endif>Private</option>
                        </select>
                        @error('book_type_id')
                        <small class="error red">{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="button-container">
                        <button onclick="edit()">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function goToViewMyBook() {
            swal({
                title: "Are you sure?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((status) => {
                    if(status) {
                        let baseUrl = window.location.origin
                        window.location = baseUrl + "/manage-my-book"
                    }
                })
        }
    </script>
@endsection
