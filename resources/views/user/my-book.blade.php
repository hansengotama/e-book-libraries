@extends('layout.index')

@section('style')
    <style>
        #my-book {
            background: #eaeaea;
        }

        .my-book-container {
            padding: 40px 25px;
        }

        .my-book-container > .books-container > .button-container > button {
            margin-bottom: 1em;
            cursor: pointer;
            padding: 6px 16px;
            border-radius: 5px;
            border-color: #eaeaea;
            background: #f8c94e;
            color: white;
        }

        .my-book-container > .books-container {
            background: white;
            height: 100%;
            padding: 20px 20px 100px 20px;
            border-radius: 15px;
        }

        .my-book-container > .books-container > .header-container {
            height: 38px;
            display: flex;
            align-items: flex-end;
        }

        .my-book-container > .books-container > .header-container > .search {
            display: flex;
        }

        .my-book-container > .books-container > .header-container > .title {
            font-weight: 500;
        }

        .my-book-container > .books-container > .header-container > .search > input {
            padding: 6px 8px;
            border: 1px solid black;
            border-right-color: white;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }

        .my-book-container > .books-container > .header-container > .search > i {
            padding: 10px 8px;
            border: 1px solid black;
            border-left-color: white;
            cursor: pointer;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }

        .my-book-container > .books-container > .custom-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 1.5em;
        }

        .my-book-container > .books-container > .custom-container > .book-container {
            padding: 1em;
            border: 1px solid #eaeaea;
            border-radius: 5px;
        }

        .my-book-container > .books-container > .custom-container > .book-container > .book-container-header {
            display: flex;
            justify-content: space-between;
        }

        .my-book-container > .books-container > .custom-container > .book-container > .book-container-body {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 10px;
        }

        .my-book-container > .books-container > .custom-container > .book-container > .book-container-body > .button-container {
            display: flex;
            flex-direction: column;
        }

        .my-book-container > .books-container > .custom-container > .book-container > .book-container-body > .button-container > .button-detail {
            padding: 6px 8px;
            background: #f8c94e;
            color: white;
            border-radius: 5px;
        }

        .my-book-container > .books-container > .custom-container > .book-container > .book-container-body > .button-container > .button-edit {
            padding: 6px 8px;
            margin-bottom: 10px;
            background: #eaeaea;
            border-radius: 5px;
        }

        .my-book-container > .books-container > .custom-container > .book-container > .book-container-body > .button-container > .button-delete {
            padding: 6px 8px;
            margin-bottom: 10px;
            background: red;
            border-radius: 5px;
        }

        .my-book-container > .books-container > .custom-container > .book-container > .book-container-header > .title {
            text-transform: uppercase;
            font-weight: 500;
        }

        .my-book-container > .books-container > .custom-container > .book-container > .book-container-header > .star-container > i.active {
            color: #f8c94e;
        }

        @media (max-width: 1500px) {
            .my-book-container > .books-container > .custom-container {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                grid-gap: 1.5em;
            }
        }


        @media (max-width: 980px) {
            .my-book-container > .books-container > .custom-container {
                display: grid;
                grid-template-columns: 1fr;
                grid-gap: 1.5em;
            }
        }
    </style>
@endsection

@section('content')
    <div id="my-book">
        <div class="my-book-container">
            <div class="books-container">
                <div class="header-container">
                    <div class="title">
                        MY BOOK
                    </div>
                    <div class="search">
                        <input type="text" placeholder="search by title" id="search" value="{{ $title }}">
                        <i class="fa fa-search" onclick="search()"></i>
                    </div>
                </div>
                <hr>
                <div class="button-container">
                    <button onclick="goToAddBook()"><i class="fa fa-plus"></i>  Add Book</button>
                </div>
                @if(count($bookData) > 0)
                <div class="custom-container">
                    @foreach($bookData as $book)
                    <div class="book-container">
                        <div class="book-container-header">
                            <div class="title">{{ $book['title'] }} | {{ $book['bookType']['name'] }} </div>
                            @if($book['rate'] != null)
                            <div class="star-container">
                                {{ $book['rate'] }} / 5 <i class="fa fa-star active"></i>
                            </div>
                            @endif
                        </div>
                        <small>created by: {{ $book['user']['name'] }} @if($book['user']['classRoom'] != null) - {{ $book['user']['classRoom']['name'] }} @endif</small>
                        <div class="book-container-body">
                            <img src="{{ asset($book['cover_url']) }}" height="200px">
                            <div class="button-container">
                                <button class="button-detail" onclick="viewDetail('{{ $book["id"] }}')"><i class="fa fa-eye"></i></button>
                                <button class="button-edit" style="margin-top: 8px" onclick="edit('{{ $book["id"] }}')"><i class="fa fa-edit"></i></button>
                                <button class="button-delete" onclick="deleteBook('{{ $book["id"] }}')"><i class="fa fa-trash" style="color: white"></i></button>
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
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        let baseUrl = window.location.origin

        $(() => {
            triggerSearch()
        })

        function goToAddBook() {
            window.location = baseUrl + "/add-my-book"
        }

        function edit(id) {
            window.location = baseUrl + "/edit-my-book/" + id
        }

        function viewDetail(id) {
            window.location = baseUrl + "/book-detail/" + id
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

        function deleteBook(id) {
            swal({
                title: "Are you sure?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.location = baseUrl + "/delete-my-book/" + id
                }
            });
        }

        function search() {
            let title = $("#search").val();

            location.href = "?title=" + title;
        }
    </script>
@endsection
