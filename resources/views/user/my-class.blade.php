@extends('layout.index')

@section('style')
    <style>
        #my-class {
            background: #eaeaea;
        }

        .my-class-container {
            padding: 40px 25px;
        }

        .my-class-container > .custom-container {
            background: white;
            height: 100%;
            padding: 20px 20px 100px 20px;
            border-radius: 15px;
        }

        .my-class-container > .custom-container > .class-container {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-gap: 1.5em;
            grid-auto-rows: minmax(100px, auto);
            margin: 10px 0 0;
        }

        .my-class-container > .custom-container > .class-container > .class {
            border: 1px solid;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 10px;
        }

        .my-class-container > .custom-container > .class-container > .class > #background {
            width: 100%;
            height: 300px;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: 50% 50%;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .my-class-container > .custom-container > .header-container {
            height: 38px;
            display: flex;
            align-items: flex-end;
        }

        .my-class-container > .custom-container > .header-container > .title {
            font-weight: 500;
        }

        @media (max-width: 1600px) {
            .my-class-container > .custom-container > .class-container {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 1300px) {
            .my-class-container > .custom-container > .class-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 1000px) {
            .my-class-container > .custom-container > .class-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 650px) {
            .my-class-container > .custom-container > .class-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div id="my-class">
        <div class="my-class-container">
            <div class="custom-container">
                <div class="header-container">
                    <div class="title">
                        {{ $class['name'] }}
                    </div>
                </div>
                <hr>
                <div class="class-container">
                    @foreach($classRoomUser as $datum)
                    <div class="class">
                        @if($datum['image_url'] != null)
                            <div id="background" style="background-image: url('{{ asset($datum['image_url']) }}')"></div>
                        @else
                            <div id="background" style="background-image: url('{{ asset("default-profile.png") }}')"></div>
                        @endif
                        <div style="margin: 15px 0">
                            {{ $datum['name'] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">

    </script>
@endsection
