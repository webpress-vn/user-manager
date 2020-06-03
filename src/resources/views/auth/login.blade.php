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
                                Welcome
                            </span>
                            <form action="{{ url('login') }}" method="POST">
                                @csrf
                                <div class="wrap-input">
                                    <input class="input" type="text" placeholder="Username or Email" name="username">
                                    <span class="focus-input" ></span>
                                </div>
                                <p class="text-danger">{{ $errors->first('username') }}</p>
                                <div class="wrap-input">
                                    <span class="btn-show-pass">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                    <input class="input" type="password" placeholder="Password" name="password">
                                    <span class="focus-input"></span>
                                </div>
                                <p class="text-danger">{{ $errors->first('password') }}</p>
                                <div class="container-login-form-btn">
                                    <div class="wrap-login-form-btn">
                                        <button type="submit" class="login-form-btn">
                                        Login
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="text-center mt-2"><a href="/forgot-password">Forgot Password</a></div>
                            <div class="text-center mt-5">
                                <span class="txt1">
                                    Don’t have an account?
                                </span>
                                <a class="txt2" href="/register">
                                    Sign Up
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
