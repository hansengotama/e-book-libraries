@extends('layout.index')

@section('style')
    <style>
        #login {
            background: #eaeaea;
        }

        .login-container {
            padding: 50px 30px;
        }

        .login-container > .form-container {
            background: white;
            height: 100%;
            padding: 20px;
            border-radius: 15px;
        }

        .login-container > .form-container > form > .login-text {
            font-weight: 500;
        }

        .login-container > .form-container > form > .input-text {
            margin-top: 8px;
            padding-top: 5px;
            padding-bottom: 5px;
            display: flex;
            flex-direction: column;
        }

        .login-container > .form-container > form > .input-text > input {
            margin-top: 8px;
            padding: 6px 8px;
        }

        .login-container > .form-container > form > .input-text > .error,
        .login-container > .form-container > form > .error {
            margin-top: 5px;
            color: red;
        }

        .login-container > .form-container > form > .button > button {
            margin-top: 8px;
            cursor: pointer;
            padding: 6px 16px;
            border-radius: 5px;
            border-color: #eaeaea;
            background: #f8c94e;
            color: white;
        }
    </style>
@endsection

@section('content')
    <div id="login">
        <div class="login-container">
            <div class="form-container">
                <form method="POST" action="{{ route('login-action') }}">
                    {!! csrf_field() !!}

                    <div class="login-text">
                        LOGIN
                    </div>
                    <hr>
                    <div class="input-text">
                        <div class="label">
                            Email
                        </div>
                        <input type="text" placeholder="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="input-text">
                        <div class="label">
                            Password
                        </div>
                        <input type="password" placeholder="password" name="password">
                        @error('password')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>
                    @error('validate')
                        <small class="error">{{ $message }}</small>
                    @enderror
                    <div class="button">
                        <button type="submit">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
