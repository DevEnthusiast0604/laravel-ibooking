@extends('Frontend::layouts.installer')

@section('title', __('Installer'))
@section('class_body', 'page installer')

@php
    enqueue_styles('installer');
    $step = request()->get('step', 0);
    $text_done = '<button type="button" class="btn btn-success btn-sm" style="font-size: 9px; padding: 2px 5px; margin-left: 10px;">DONE</button>';
@endphp

@section('content')
    <div class="installer-wrapper">
        <h1 class="logo"><a href="https://booteam.co">iBooking</a></h1>
        <div class="inner">
            @if($step < 7)
                <p class="mb-4"><b>Importing Demo Data...</b></p>
            @else
                <p class="mb-4"><b>Import Demo Data Successfully</b></p>
            @endif

            <ol>
                <li class="mb-1">
                    Import Post data
                    @if($step > 1)
                        {!! $text_done !!}
                    @endif
                </li>
                <li class="mb-1">
                    Import Page data
                    @if($step > 2)
                        {!! $text_done !!}
                    @endif
                </li>
                <li class="mb-1">
                    Import Hotel data
                    @if($step > 3)
                        {!! $text_done !!}
                    @endif
                </li>
                <li class="mb-1">
                    Import Apartment data
                    @if($step > 4)
                        {!! $text_done !!}
                    @endif
                </li>
                <li class="mb-1">
                    Import Car data
                    @if($step > 5)
                        {!! $text_done !!}
                    @endif
                </li>
                <li class="mb-1">
                    Import Space data
                    @if($step > 6)
                        {!! $text_done !!}
                    @endif
                </li>
                <li class="mb-1">
                    Import Tour data
                    @if($step > 7)
                        {!! $text_done !!}
                    @endif
                </li>
                <li class="mb-1">
                    Import Beauty data
                    @if($step > 8)
                        {!! $text_done !!}
                    @endif
                </li>
                <li class="mb-1">
                    Import Menu data
                    @if($step > 9)
                        {!! $text_done !!}
                    @endif
                </li>
                <li class="mb-1">
                    Import Settings data
                    @if($step > 10)
                        {!! $text_done !!}
                    @endif
                </li>
            </ol>

            @if($step < 11)
                <p class="mb-4">Please do not turn off your browser while importing demo data</p>
                <div class="d-flex align-items-center mb-3">
                    <a href="{{url('installer/import-data?step=1')}}" class="btn btn-outline-primary mr-3">Import now!</a>
                </div>
            @else
                <a href="{{url('/')}}" class="btn btn-outline-success mt-3 mb-3">Go to your site now!</a>
            @endif
        </div>
    </div>
@stop

