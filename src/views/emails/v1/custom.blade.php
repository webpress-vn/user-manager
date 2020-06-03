@extends('user_component::emails.v1.master')

@section('subject')

{{$subject}}

@stop

@section('greeting')

{{$greeting}}

@stop

@section('content')

{!! $content !!}

@stop
