<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset("logo.png") }}">
    <title>EBook Libraries</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <style>
        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
        }

        input:active,
        input:focus,
        button:active,
        button:focus,
        textarea:active,
        textarea:focus,
        select:active,
        select:focus {
            outline: none;
        }

        hr {
            border: 1px solid #eaeaea;
        }

        table > thead > tr > th {
            border-bottom: 2px solid #eaeaea;
            padding: 5px;
            font-weight: 500 !important;
            text-align: center;
        }

        table > tbody > tr > td {
            border-bottom: 1px solid #eaeaea;
            padding: 10px 5px;
            text-align: center;
        }


        table > tbody > tr > td > a {
            padding: 6px;
            border-radius: 5px;
            color: white !important;
            cursor: pointer;
        }

        table > tbody > tr > td > a.delete {
            background: red;
        }

        table > tbody > tr > td > a.edit {
            background: #f8c94e;
        }

        .bg-yellow {
            background: #f8c94e;
        }

        .white {
            color: white;
        }

        .red {
            color: red;
        }

        .modal-body > .form-container > .input-container {
            width: 100%;
        }

        .modal-body > .form-container > .input-container > .title {
            margin-bottom: 8px;
        }

        .modal-body > .form-container > .input-container > input,
        .modal-body > .form-container > .input-container > select {
            width: 100%;
            padding: 6px 8px;
        }

        .modal-body > .form-container > .input-container > select {
            background: white;
            border: 1px solid #757575;
            height: 40px;
        }

        button {
            border: none;
        }

        .pagination > .page-item.active > .page-link {
            background-color: #f8c94e !important;
            color: white;
            border-color: #f8c94e;
        }

        .pagination > .page-item > .page-link {
            color: black;
        }
    </style>
    @yield('style')
</head>
<body>
@include('layout.header')
@yield('content')
@include('layout.footer')

@yield('modal')

<script
    src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs="
    crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@yield('script')
</body>
</html>
