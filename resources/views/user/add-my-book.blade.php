@extends('layout.index')

@section('style')
    <style>
        #add-my-book {
            background: #eaeaea;
        }

        .add-my-book-container {
            padding: 40px 25px;
        }

        .add-my-book-container > .form-container {
            background: white;
            height: 100%;
            padding: 20px 20px 100px 20px;
            border-radius: 15px;
        }

        .add-my-book-container > .form-container > .header-container {
            display: flex;
            align-items: center;
        }

        .add-my-book-container > .form-container > .header-container > .button-container > button {
            cursor: pointer;
            padding: 6px 16px;
            border-radius: 5px;
            border-color: #eaeaea;
            background: #f8c94e;
            color: white;
        }

        .add-my-book-container > .form-container > form > .button-container > button {
            cursor: pointer;
            padding: 6px 16px;
            border-radius: 5px;
            border-color: #eaeaea;
            background: #f8c94e;
            color: white;
        }

        .add-my-book-container > .form-container > form > .input-container {
            margin-bottom: 1em;
        }

        .add-my-book-container > .form-container > form > .input-container > .title {
            margin-bottom: 8px;
        }

        .add-my-book-container > .form-container > form > .input-container > input,
        .add-my-book-container > .form-container > form > .input-container > textarea,
        .add-my-book-container > .form-container > form > .input-container > select {
            width: 100%;
            padding: 6px 8px;
            margin-bottom: 5px;
            border: 1px solid;
            border-radius: 6px;
        }

        .add-my-book-container > .form-container > .header-container > .title {
            font-weight: 500;
        }
    </style>
@endsection

@section('content')
    <div id="add-my-book">
        <div class="add-my-book-container">
            <div class="form-container">
                <div class="header-container">
                    <div class="title">
                        ADD BOOK
                    </div>
                    <div class="button-container">
                        <button onclick="goToViewMyBook()">View My Book</button>
                    </div>
                </div>
                <hr>
                <form method="POST" action="{{ route('add-my-book') }}" enctype="multipart/form-data">
                    {!! csrf_field() !!}

                    <div class="input-container">
                        <div class="title">
                            Type <span class="red">*</span>
                        </div>
                        <select name="book_type_id" required>
                            @foreach($bookTypes as $bookType)
                                <option value="{{ $bookType['id'] }}" {{ (old("book_type_id") == $bookType['id'] ? "selected" : "") }}>
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
                        <input type="text" placeholder="Title" name="title" required>
                        @error('title')
                        <small class="error red">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="input-container">
                        <div class="title">
                            Description <span class="red">*</span>
                        </div>
                        <textarea placeholder="Description" name="description" id="" cols="30" rows="10" required></textarea>
                        @error('description')
                        <small class="error red">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="input-container">
                        <div class="title">
                            Cover <span class="red">*</span>
                        </div>
                        <input type="file" name="cover" accept="image/*" required>
                        @error('cover')
                        <small class="error red">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="input-container">
                        <div class="title">
                            File <span class="red">*</span>
                        </div>
                        <input type="file" name="file" accept="application/pdf, application/msword" required>
                        @error('cover')
                        <small class="error red">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="input-container">
                        <div class="title">
                            Privilege <span class="red">*</span>
                        </div>
                        <select name="is_public" required>
                            <option value=true>Public</option>
                            <option value=true>Private</option>
                        </select>
                        @error('book_type_id')
                        <small class="error red">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="button-container">
                        <button onclick="save()">Save</button>
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
