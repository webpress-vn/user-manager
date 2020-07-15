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
            <div class="container-fluid">
                <div class="row">
                    <div class="container-login">
                        <div class="wrap-account">
                            <div class="login-form validate-form">
                                <span class="login-form-title mb-5">
                                    Đăng ký
                                </span>
                                <form action="{!! route('register') !!}" method="POST">
                                    @csrf
                                    <div class="d-flex justify-content-between">
                                        <div class="wrap-input-form-two">
                                            <input class="input" type="text" placeholder="Họ..." name="first_name">
                                            <span class="focus-input" ></span>
                                        </div>
                                        <div class="wrap-input-form-two">
                                            <input class="input" type="text" placeholder="Tên..." name="last_name">
                                            <span class="focus-input" ></span>
                                        </div>
                                    </div>
                                    <div class="wrap-input">
                                        <input class="input" type="text" placeholder="Tên tài khoản (*)" name="username">
                                        <span class="focus-input" ></span>
                                    </div>
                                    <p class="text-danger">{{ $errors->first('username') }}</p>
                                    <div class="wrap-input">
                                        <input class="input" type="password" placeholder="Mật khẩu (*)" name="password">
                                        <span class="focus-input"></span>
                                    </div>
                                    <p class="text-danger">{{ $errors->first('password') }}</p>
                                    <div class="wrap-input">
                                        <input class="input" type="password" placeholder="Xác nhận mật khẩu (*)" name="password_confirmation">
                                        <span class="focus-input"></span>
                                    </div>
                                    <p class="text-danger">{{ $errors->first('password') }}</p>
                                    <div class="wrap-input">
                                        <input class="input" type="email" placeholder="Email" name="email">
                                        <span class="focus-input"></span>
                                    </div>
                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                    <div class="wrap-input">
                                        <input class="input" type="email" placeholder="Xác nhận Email" name="email_confirmation">
                                        <span class="focus-input"></span>
                                    </div>
                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                    <div class="wrap-input">
                                        <input class="input" type="text" placeholder="Địa chỉ" name="address">
                                        <span class="focus-input"></span>
                                    </div>
                                    <p class="text-danger">{{ $errors->first('address') }}</p>
                                    <div class="wrap-input">
                                        <input class="input" type="number" placeholder="Số điện thoại (*)" name="phone_number">
                                        <span class="focus-input"></span>
                                    </div>
                                    <p class="text-danger">{{ $errors->first('phone_number') }}</p>
                                    <div class="container-login-form-btn">
                                        <div class="wrap-login-form-btn">
                                            <button type="submit" class="login-form-btn">
                                            Đăng ký
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <div class="text-center mt-5">
                                    <span class="txt1">
                                        Đã có tài khoản ?
                                    </span>
                                    <a class="txt2" href="/login">
                                        Đăng nhập
                                    </a>
                                </div>
                                <div class="text-center mt-3">
                                    <a class="txt2" href="/">
                                        Trang chủ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
