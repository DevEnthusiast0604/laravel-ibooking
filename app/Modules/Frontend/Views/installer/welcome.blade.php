@extends('Frontend::layouts.installer')

@section('title', __('Installer'))
@section('class_body', 'page installer')

@section('content')
    <div class="installer-wrapper">
        <h1 class="logo"><a href="https://booteam.co">iBooking</a></h1>
        <div class="inner">
            <p class="mb-4">Welcome to <b>iBooking</b> installer page. Before getting started, we need some information on the database. You will need to know the following items before proceeding.</p>

            <ol class="mb-4">
                <li>Database name</li>
                <li>Database username</li>
                <li>Database password</li>
                <li>Database host</li>
            </ol>
            <p>We’re going to use this information to create a .env file. <b>If for any reason this automatic file creation doesn’t work, don’t worry. All this does is fill in the database information to a configuration file. You may also simply open .env in a text editor, fill in your information, and save it.</b></p>

            <p class="mb-4">In all likelihood, these items were supplied to you by your Web Host. If you don’t have this information, then you will need to contact them before you can continue. If you’re all ready…</p>

            <a class="btn btn-outline-secondary mb-3" href="{{url('installer/config-database')}}">Let's go!</a>
        </div>
    </div>
@stop

