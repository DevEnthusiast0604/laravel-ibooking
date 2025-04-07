@extends('Frontend::layouts.installer')

@section('title', __('Installer'))
@section('class_body', 'page installer')

@php
    if(!isset($post_data)){
        $post_data = [];
    }
    $db_name = isset($post_data['db_name']) ? $post_data['db_name'] : '';
    $db_username = isset($post_data['db_username']) ? $post_data['db_username'] : '';
    $db_password = isset($post_data['db_password']) ? $post_data['db_password'] : '';
    $db_port = isset($post_data['db_port']) ? $post_data['db_port'] : '3306';
    $db_host = isset($post_data['db_host']) ? $post_data['db_host'] : 'localhost';
@endphp

@section('content')
    <div class="installer-wrapper">
        <h1 class="logo"><a href="https://booteam.co">iBooking</a></h1>
        <div class="inner">
            <form method="POST" action="{{url('installer/config-database')}}">
                <h4 class="mb-4">Database Configuration</h4>
                <p class="mb-4">Below you should enter your database connection details. If you’re not sure about these, contact your host.</p>

                <table class="w-100">
                    <colgroup width="20%"></colgroup>
                    <colgroup width="30%"></colgroup>
                    <colgroup width="50%"></colgroup>
                    <tr>
                        <td class="pr-1 pb-4"><b>Database Name</b></td>
                        <td class="pb-4"><input type="text" name="db_name" class="form-control" value="{{$db_name}}"/></td>
                        <td class="pl-4 pb-4">The name of the database you want to use with iBooking.</td>
                    </tr>
                    <tr>
                        <td class="pr-1 pb-4"><b>Username</b></td>
                        <td class="pb-4"><input type="text" name="db_username" class="form-control" value="{{$db_username}}"/></td>
                        <td class="pl-4 pb-4">Your database username.</td>
                    </tr>
                    <tr>
                        <td class="pr-1 pb-4"><b>Password</b></td>
                        <td class="pb-4"><input type="text" name="db_password" class="form-control" value="{{$db_password}}"/></td>
                        <td class="pl-4 pb-4">Your database password.</td>
                    </tr>
                    <tr>
                        <td class="pr-1 pb-4"><b>Database Port</b></td>
                        <td class="pb-4"><input type="text" name="db_port" value="{{$db_port}}" class="form-control"/></td>
                        <td class="pl-4 pb-4">Database port</td>
                    </tr>
                    <tr>
                        <td class="pr-1 pb-4"><b>Database Host</b></td>
                        <td class="pb-4"><input type="text" name="db_host" value="{{$db_host}}" class="form-control"/></td>
                        <td class="pl-4 pb-4">You should be able to get this info from your web host, if localhost doesn’t work.</td>
                    </tr>
                </table>

                <div class="d-flex mb-3 align-items-center">
                    <button type="submit" class="btn btn-outline-secondary mr-3">Submit</button>
                    @if(isset($message))
                        <div class="text text-danger">{{ $message }}</div>
                    @endif
                </div>
            </form>
        </div>
    </div>
@stop

