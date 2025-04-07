@extends('Frontend::layouts.error')

@section('content')
    <h1 class="error-number">{{__('500')}}</h1>
    <p class="mini-text">{{__('Ooops!')}}</p>
    <p class="error-text">{{__('Internal Server Error!')}}</p>
    <a href="{{url('/')}}" class="btn btn-secondary mt-5">{{__('Go Back')}}</a>
@endsection