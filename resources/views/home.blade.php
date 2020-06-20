@extends('layout.index')

@section('style')
    <style>
        #home {
            background: #eaeaea;
        }

        .home-container {
            padding: 50px 30px;
            display: flex;
        }

        .home-container > .books-container {
            background: white;
            height: 100%;
            padding: 20px;
            border-radius: 15px;
            flex: 2;
            margin-right: 30px;
        }

        .home-container > .filter-book-container {
            background: white;
            height: 100%;
            padding: 20px;
            border-radius: 15px;
            flex: 1;
            position: sticky;
            top: 50px;
        }

        .home-container > .books-container > .header-container,
        .home-container > .filter-book-container > .title {
            height: 38px;
            display: flex;
            align-items: flex-end;
        }

        .home-container > .filter-book-container > .title,
        .home-container > .books-container > .header-container > .title {
            font-weight: 500;
        }

        .home-container > .books-container > .header-container > .search {
            display: flex;
            align-items: center;
        }

        .home-container > .books-container > .header-container > .search > input {
            padding: 6px 8px;
            border: 1px solid black;
            border-right-color: white;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }

        .home-container > .books-container > .header-container > .search > i {
            padding: 10px 8px;
            border: 1px solid black;
            border-left-color: white;
            cursor: pointer;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }

        .home-container > .books-container > .custom-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-gap: 1em;
        }

        .home-container > .books-container > .custom-container > .book-container {
            margin-bottom: 20px;
        }

        .home-container > .books-container > .custom-container > .book-container {
            padding: 1em;
            border: 1px solid #eaeaea;
            border-radius: 5px;
        }

        .home-container > .books-container > .custom-container > .book-container > .book-container-header {
            display: flex;
            justify-content: space-between;
        }

        .home-container > .books-container > .custom-container > .book-container > .book-container-body {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 10px;
        }

        .home-container > .books-container > .custom-container > .book-container > .book-container-body > .button-container {
            display: flex;
            flex-direction: column;
        }

        .home-container > .books-container > .custom-container > .book-container > .book-container-body > .button-container > .button-detail {
            padding: 6px 8px;
            background: #f8c94e;
            color: white;
            border-radius: 5px;
        }

        .home-container > .books-container > .custom-container > .book-container > .book-container-body > .button-container > .button-download {
            padding: 6px 8px;
            margin-bottom: 10px;
            background: #eaeaea;
            border-radius: 5px;
        }

        .home-container > .books-container > .custom-container > .book-container > .book-container-body > .button-container > .button-set-private {
            padding: 6px 8px;
            margin-bottom: 10px;
            background: orange;
            border-radius: 5px;
        }

        .home-container > .books-container > .custom-container > .book-container > .book-container-body > .button-container > .button-delete {
            padding: 6px 8px;
            margin-bottom: 10px;
            background: red;
            border-radius: 5px;
        }

        .home-container > .books-container > .custom-container > .book-container > .book-container-header > .title {
            text-transform: uppercase;
            font-weight: 500;
        }

        .home-container > .books-container > .custom-container > .book-container > .book-container-header > .star-container > i.active {
            color: #f8c94e;
        }

        .home-container > .filter-book-container > .custom-container {
            margin-bottom: 20px;
        }

        .home-container > .filter-book-container > .custom-container > .title {
            margin-bottom: 10px;
        }

        .home-container > .filter-book-container > .custom-container > select {
            width: 100%;
            height: 38px;
            text-indent: 6px;
        }

        @media (max-width: 1600px) {
            .home-container > .books-container {
                flex: 3;
            }
        }

        @media (max-width: 1450px) {
            .home-container {
                flex-direction: column-reverse;
            }

            .home-container > .filter-book-container {
                position: unset;
                margin-bottom: 1em;
            }

            .home-container > .books-container {
                margin-right: 0;
            }

            .home-container > .filter-book-container > .title {
                height: auto;
            }
        }

        @media (max-width: 1000px) {
            .home-container > .books-container > .custom-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div id="home">
        <div class="home-container">
            <div class="books-container">
                <div class="header-container">
                    <div class="title">
                        BOOK
                    </div>
                    <div class="search">
                        <input type="text" placeholder="search by title" id="search" value="{{ $title }}">
                        <i class="fa fa-search" onclick="search()"></i>
                    </div>
                </div>
                <hr>
                <div class="custom-container">
                    @if(count($books) > 0)
                    @foreach($books as $book)
                        <div class="book-container">
                            <div class="book-container-header">
                                <div class="title">{{ $book['title'] }} | {{ $book['bookType']['name'] }} </div>
                                @if($book['rate'] != null)
                                    <div class="star-container">
                                        {{ $book['rate'] }} / 5 <i class="fa fa-star active"></i>
                                    </div>
                                @endif
                            </div>
                            <small style="display: block; margin-top: 7px">{{ \Carbon\Carbon::parse($book['created_at'])->format("d M Y") }}</small>
                            <small>created by: {{ $book['user']['name'] }} @if($book['user']['classRoom'] != null) - {{ $book['user']['classRoom']['name'] }} @endif</small>
                            <div class="book-container-body">
                                <img src="{{ asset($book['cover_url']) }}" height="200px">
                                <div class="button-container">
                                    <button class="button-download" onclick="download('{{ $book["file_url"] }}')"><i class="fa fa-download"></i></button>
                                    <button class="button-detail" onclick="viewDetail('{{ $book["id"] }}')"><i class="fa fa-eye"></i></button>
                                    @if(\Illuminate\Support\Facades\Auth::user() && \Illuminate\Support\Facades\Auth::user()->role == "admin")
                                    <button class="button-set-private" style="margin-top: 8px" onclick="setPrivateBook('{{ $book["id"] }}')"><i class="fa fa-key" style="color: white"></i></button>
                                    <button class="button-delete" onclick="deleteBook('{{ $book["id"] }}')"><i class="fa fa-trash" style="color: white"></i></button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @else
                    <div>
                        Book not found
                    </div>
                    @endif
                </div>
            </div>
            <div class="filter-book-container">
                <div class="title">FILTER BY</div>
                <hr>
                <div class="custom-container">
                    <div class="title">Type</div>
                    <select id="book-type-id" required onchange="onChangeFilter()">
                        <option value=null>All</option>
                        @foreach($bookTypes as $bookType)
                            <option value="{{ $bookType['id'] }}" {{ ($bookTypeId == $bookType['id'] ? "selected" : "") }}>
                                {{ $bookType['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="custom-container">
                    <div class="title">Class</div>
                    <select id="class-id" required onchange="onChangeFilter()">
                        <option value=null>All</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom['id'] }}" {{ ($classId == $classroom['id'] ? "selected" : "") }}>
                                {{ $classroom['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="custom-container">
                    <div class="title">User</div>
                    <select id="user-id" required onchange="onChangeFilter()">
                        <option value=null>All</option>
                        @foreach($users as $user)
                            <option value="{{ $user['id'] }}" {{ ($userId == $user['id'] ? "selected" : "") }}>
                                {{ $user['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        let baseUrl = window.location.origin

        $(() => {
            triggerSearch()
        })

        function onChangeFilter() {
            let bookTypeId = $("#book-type-id option:selected").val();
            let classId = $("#class-id option:selected").val();
            let userId = $("#user-id option:selected").val();
            let title = $("#search").val();

            location.href = "?book_type_id=" + bookTypeId + "&class_id=" + classId + "&user_id=" + userId + "&title=" + title;
        }

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

        function search() {
            onChangeFilter()
        }

        function download(url) {
            window.location = baseUrl + "/download-file/" + url
        }

        function viewDetail(id) {
            window.location = baseUrl + "/book-detail/" + id
        }

        function setPrivateBook(id) {
            swal({
                title: "Are you sure?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willSetPrivate) => {
                if (willSetPrivate) {
                    window.location = baseUrl + "/set-book-private/" + id
                }
            });
        }

        function deleteBook(id) {
            swal({
                title: "Are you sure?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.location = baseUrl + "/delete-book/" + id
                }
            });
        }
    </script>
@endsection
