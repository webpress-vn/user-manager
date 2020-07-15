@extends('layout.master')
@section('content')
<section style="height: 300px">
    @if (Route::has('verify-not-me'))
    <div class="d-flex justify-content-center">
        <p style="font-size: 14 ; padding: 40px">Thank you for informing</p>
    </div>
    @else
    <div>
        email is verify
    </div>
    @endif
</section>
@endsection
@section('footer')
