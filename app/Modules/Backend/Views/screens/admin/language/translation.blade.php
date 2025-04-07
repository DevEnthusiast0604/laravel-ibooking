<?php
/**
 * Created by PhpStorm.
 * User: JreamOQ ( jreamoq@gmail.com )
 * Date: 12/10/20
 * Time: 13:12
 */
?>
@extends('Backend::layouts.master')

@section('title', __('Translation'))

@php
    admin_enqueue_styles([
        'gmz-datatables',
        'gmz-dt-global',
        'gmz-dt-multiple-tables'
    ]);
    admin_enqueue_scripts('gmz-datatables');
@endphp

@section('content')

    <div class="layout-top-spacing translation-page">
        <div class="statbox widget box box-shadow">

            <div class="widget-header mb-5">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="d-flex align-items-center trans-heading">
                            <h4 class="rtl-ml-3">{{__('Translation')}}</h4>
                            <a class="btn btn-primary ml-3 gmz-link-action gmz-link-scan-translation btn-xs btn-success"  data-page-loading="true" href="javascript:void(0);" data-action="{{dashboard_url('scan-translation')}}" data-params="{{ base64_encode(json_encode(['scan' => true, 'lang' => $lang])) }}">{{ __('Scan Text') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ dashboard_url('update-translation') }}" class="form gmz-form-action trans-form" method="post" data-encode="true">
                @include('Backend::components.loader')
                <div class="d-flex mb-4 justify-content-between trans-choose-lang">
                    <div class="form-inline">
                        <label for="hh-choose-langs" class="mr-2">{{ __('Languages') }}</label>
                        <select id="gmz-select-langs" data-plugin="customselect" class="form-control form-control-sm min-w-200" name="lang" data-url="{{ dashboard_url('translation') }}">
                            <option value="none">{{ __('Select language') }}</option>
                            @if(!empty($langs))
                                @foreach($langs as $k => $v)
                                    <option {{ ($k == $lang) ? 'selected' : '' }} value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success right btn-scan-translation">
                        <span class="btn-label"><i class="mdi mdi-check-all"></i></span>
                        {{__('Save Translation')}}
                    </button>
                </div>
                @if($lang == 'none' || !isset($langs[$lang]))
                    <div class="alert alert-warning">{{ __('Please select a language before translating') }}</div>
                @endif

                <div class="translation-search">
                    <input id="input-search-translation" class="form-control" type="text" placeholder="{{__('Enter search text...')}}"/>
                    <button type="button" class="btn btn-success"><i class="ti-search mr-1"></i> {{__('Search')}}</button>
                </div>

                <div class="table-responsive table-translations">
                    @if(!empty($strings))
                        <table class="table mb-0 h-100">
                            <colgroup width="35%"></colgroup>
                            <colgroup></colgroup>
                            <thead class="thead-light">
                            <tr>
                                <th>{{ __('Origin Text') }} ({{ __(':number items', ['number' => count($strings)]) }})</th>
                                <th>{{ __('Translation Text') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($strings as $k => $v)
                                <tr>
                                    <th scope="row" class="align-middle">{{ $v }}</th>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="{{ base64_encode($v) . '_' . time() }}" value="{{ isset($translation[$v]) ? $translation[$v] : '' }}"/>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-danger">{{ __('No Text to translate') }}</div>
                    @endif
                </div>

                <button type="submit" class="mt-4 btn btn-success">
                    <span class="btn-label"><i class="mdi mdi-check-all"></i></span>
                    {{ __('Save Translation') }}
                </button>
            </form>
        </div>
    </div>
@stop