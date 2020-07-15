@extends('layout.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{('Hello')}}
                    {{$authUser->username}}
                    {{ __('Your account is verified') }}</div>
                <div class="d-flex">
                    <div class="card-body">
                        <a href="{{ route('home') }}"> <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here return home') }}</button></a>
                    </div>

                    <div class="card-body">
                        <a href="{{ route('account') }}"><button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('Đăng nhập ngay') }}</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
