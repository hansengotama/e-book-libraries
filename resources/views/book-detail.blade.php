@extends('layout.index')

@section('style')
    <style>
        #book-detail {
            background: #eaeaea;
        }

        .book-detail-container {
            padding: 40px 25px;
        }

        .book-detail-container > .custom-container {
            background: white;
            height: 100%;
            padding: 20px 20px 100px 20px;
            border-radius: 15px;
        }

        .book-detail-container > .custom-container > .title-container {
            display: flex;
            justify-content: space-between;
        }

        .book-detail-container > .custom-container > .title-container > .title {
            font-weight: 500;
        }

        .book-detail-container > .custom-container > .title-container > .star-container > i.active {
            color: #f8c94e;
        }

        .book-detail-container > .custom-container > .body-container {
            display: flex;
            justify-content: space-between;
        }

        .book-detail-container > .custom-container > .body-container > div > button {
            margin-top: 8px;
            cursor: pointer;
            padding: 6px 16px;
            border-radius: 5px;
            border-color: #eaeaea;
            background: #f0f0f0;
            height: 40px;
        }

        .book-detail-container > .custom-container > .body-container > div {
            display: flex;
            justify-items: flex-end;
            flex-direction: column;
        }

        .book-detail-container > .custom-container > .body-container > div > .stars-container {
            display: flex;
            justify-content: flex-end;
        }

        .book-detail-container > .custom-container > .body-container > div > .stars-container > .fa-star {
            font-size: 30px;
            margin-left: 5px;
            color: #eaeaea;
            cursor: pointer;
        }

        .book-detail-container > .custom-container > .body-container > div > .stars-container > .fa-star.active {
            color: #f8c94e;
        }

        .book-detail-container > .custom-container > .description-container {
            margin-top: 1em;
        }

        .book-detail-container > .custom-container > .comments-container {
            margin-top: 1em;
        }

        .book-detail-container > .custom-container > .comments-container > form > .comment {
            width: 100%;
            border-radius: 5px;
            border: 1px solid;
            padding: 6px 8px;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .book-detail-container > .custom-container > .comments-container > form > button {
            margin-bottom: 1em;
            cursor: pointer;
            padding: 6px 16px;
            border-radius: 5px;
            border-color: #eaeaea;
            background: #f8c94e;
            color: white;
        }

        .book-detail-container > .custom-container > .comments-container > form > .title,
        .book-detail-container > .custom-container > .comments-container > .title {
            font-weight: 500;
        }

        .book-detail-container > .custom-container > .comments-container > .view-comments {
            margin-top: 15px;
            margin-bottom: 40px;
        }

        .book-detail-container > .custom-container > .comments-container > .view-comments  > .comment-container {
            display: flex;
        }

        .book-detail-container > .custom-container > .comments-container > .view-comments  > .comment-container > .comment-info {
            margin-left: 15px;

        }

        .book-detail-container > .custom-container > .comments-container > .view-comments  > .comment-container > .comment-info > .profile-info {
            display: flex;
        }

        .book-detail-container > .custom-container > .comments-container > .view-comments  > .comment-container > .comment-info > .profile-info > .name {
            font-weight: 500;
        }

        .book-detail-container > .custom-container > .comments-container > .view-comments  > .comment-container > .comment-info > .profile-info > .date {
            margin-left: 5px;
            color: #4e4e4e;
        }
    </style>
@endsection

@section('content')
    <div id="book-detail">
        <div class="book-detail-container">
            <div class="custom-container">
                <div class="title-container">
                    <div class="title">
                        {{ $book['title'] }} | {{ $book['bookType']['name'] }}
                    </div>
                    @if($book['rate'] != null)
                        <div class="star-container">
                            {{ $book['rate'] }} / 5 <i class="fa fa-star active"></i>
                        </div>
                    @endif
                </div>
                <hr>
                <div class="body-container">
                    <a href="{{ asset($book['cover_url']) }}" target="_blank">
                        <img src="{{ asset($book['cover_url']) }}" width="300px" style="cursor: pointer">
                    </a>
                    <div>
                        @if(\Illuminate\Support\Facades\Auth::check())
                            <div class="stars-container">
                                <i class="fa fa-star @if($book["my_rating"] >= 1) active @endif" onclick="rateBook(1)"></i>
                                <i class="fa fa-star @if($book["my_rating"] >= 2) active @endif" onclick="rateBook(2)"></i>
                                <i class="fa fa-star @if($book["my_rating"] >= 3) active @endif" onclick="rateBook(3)"></i>
                                <i class="fa fa-star @if($book["my_rating"] >= 4) active @endif" onclick="rateBook(4)"></i>
                                <i class="fa fa-star @if($book["my_rating"] == 5) active @endif" onclick="rateBook(5)"></i>
                            </div>
                        @else
                            <div class="stars-container red">Please login to rate this book</div>
                        @endif
                        <button onclick="download('{{ $book["file_url"] }}')"><i class="fa fa-download"></i> Download</button>
                        <input type="text" value="{{ $book['id'] }}" id="book-id" hidden>
                    </div>
                </div>
                <div class="">
                    <small>created by: {{ $book['user']['name'] }} @if($book['user']['classRoom'] != null) - {{ $book['user']['classRoom']['name'] }} @endif</small>
                </div>
                <div class="description-container">
                    {{ $book['description'] }}
                </div>
                <hr>
                <div class="comments-container">
                    @if(\Illuminate\Support\Facades\Auth::check())
                        <form action="{{ route('add-book-comment') }}" method="POST">
                            {!! csrf_field() !!}

                            <div class="title">Comment :</div>
                            <input type="text" class="comment" placeholder="comment" name="comment">
                            <input type="text" class="comment" placeholder="comment" name="book_id" value="{{ $book['id'] }}" hidden>
                            <button type="submit">
                                Submit
                            </button>
                        </form>
                    @else
                        <div class="red" style="margin-bottom: 10px">Please login to comment this book</div>
                    @endif
                    <div class="title">View comments :</div>
                    @if(count($book['bookComments']) <= 0)
                        <div>Comment not found</div>
                    @else
                        @foreach($book['bookComments'] as $datum)
                            <div class="view-comments">
                                <div class="comment-container">
                                    <div class="image">
                                        @if($datum['user']['image_url'] != null)
                                            <img src="{{ asset($datum['user']['image_url']) }}" width="50px" height="50px" style="border-radius: 50%">
                                        @else
                                            <img src="{{ asset("default-profile.png") }}" width="50px" height="50px" style="border-radius: 50%">
                                        @endif
                                    </div>
                                    <div class="comment-info">
                                        <div class="profile-info">
                                            <div class="name">{{ $datum['user']['name'] }}</div>
                                            <div class="date">{{ \Carbon\Carbon::parse($datum['created_at'])->format("d M Y") }}</div>
                                        </div>
                                        <div>{{ $datum['comment'] }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        let baseUrl = window.location.origin

        function download(url) {
            window.location = baseUrl + "/download-file/" + url
        }

        function rateBook(rate) {
            swal({
                title: "Are you sure to rate this book with " + rate + " star ?",
                icon: "info",
                buttons: true,
                dangerMode: true,
            })
            .then((status) => {
                if(status) {
                    let bookId = $("#book-id").val()
                    window.location = baseUrl + "/rate-book?book_id=" + bookId + "&rate=" + rate
                }
            })
        }
    </script>
@endsection
