@extends('Frontend::layouts.installer')

@section('title', __('Installer'))
@section('class_body', 'page installer')

@section('content')
    <div class="installer-wrapper">
        <h1 class="logo"><a href="https://booteam.co">iBooking</a></h1>
        <div class="inner">
            <p class="mb-4"><b>Database connection is successful</b></p>
            <p class="mb-4">We’re going to use this information to create a .env file and contact to your database successfully. Now you can <b>install demo data</b> for your website or skip it if you don't want to.</p>

            <div class="d-flex align-items-center mb-3">
                <a class="btn btn-outline-primary mr-3" href="{{url('installer/import-data')}}">Install demo data</a>
                <a class="btn btn-outline-secondary" href="{{url('installer/not-import-data')}}">Not now!</a>
            </div>
        </div>
    </div>
@stop

