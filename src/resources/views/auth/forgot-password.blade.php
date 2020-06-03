<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" media="screen" href="{{url('/css/app.css')}}" />
        <script src="{{url('js/app.js')}}"></script>
        <title>Login</title>
    </head>
    <body>
        <div class="limiter">
            <div class="container">
                <div class="container-login">
                    <div class="wrap-login">
                        <div class="login-form validate-form">
                            <span class="login-form-title mb-5">
                                Forgot Password
                            </span>
                            @if (session('status'))
                            <div class="text-success mt-2 mb-5">
                                {{ session('status') }}
                            </div>
                            @endif
                            <form action="{{ route('password.forgot') }}" method="POST">
                                @csrf
                                <div class="wrap-input">
                                    <input class="input" type="email" placeholder="Email" name="email">
                                    <span class="focus-input" ></span>
                                </div>
                                <p class="text-danger">{{ $errors->first('email') }}</p>
                                <div class="container-login-form-btn">
                                    <div class="wrap-login-form-btn">
                                        <button type="submit" class="login-form-btn">
                                        Send reset password
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="text-center mt-5">
                                <span class="txt1">
                                    Don’t have an account?
                                </span>
                                <a class="txt2" href="/register">
                                    Sign Up
                                </a>
                                Or
                                <a class="txt2" href="/login">
                                    Login
                                </a>
                            </div>
                            <div class="text-center mt-3">
                                <a class="txt2" href="/">
                                    Home Page
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
