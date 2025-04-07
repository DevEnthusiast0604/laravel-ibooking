@extends('Frontend::layouts.error')

@section('content')
    <h1 class="error-number">{{__('404')}}</h1>
    <p class="mini-text">{{__('Ooops!')}}</p>
    <p class="error-text mb-4 mt-1">{{__('The page you requested was not found!')}}</p>
    <a href="{{url('/')}}" class="btn btn-primary mt-5">{{__('Go Back')}}</a>
@endsection