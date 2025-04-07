<?php
/**
 * Created by PhpStorm.
 * User: JreamOQ ( jreamoq@gmail.com )
 * Date: 12/9/20
 * Time: 09:54
 */
?>
@extends('Backend::layouts.master')

@section('title', __('Languages'))

@php
    admin_enqueue_styles([
        'gmz-datatables',
        'gmz-dt-global',
        'gmz-dt-multiple-tables',
        'flat-icon',
        'jquery-ui',
        'footable'
    ]);
    admin_enqueue_scripts([
        'gmz-datatables',
        'jquery-ui',
        'footable'
        ]);
@endphp

@section('content')

    <div class="layout-top-spacing">
        <div class="statbox widget box box-shadow">

            <div class="d-flex align-items-center justify-content-between">
                <h4>{{__('Languages')}}</h4>
            </div>

            <hr />

            <div class="row mt-4">
                <div class="col-lg-5 mb-5">
                    <h6>{{__('Setup Language')}}</h6>
                    <form action="{{ dashboard_url('update-language') }}" class="form gmz-form-action mt-3"
                          data-reload-time="1500"
                          method="post">
                        @include('Backend::components.loader')
                        @php
                            $edit_id = $isEdit? $currentLang->id: '';
                            $edit_lang = $isEdit? $currentLang->code: '';
                            $edit_name = $isEdit? $currentLang->name: '';
                            $edit_ficon = $isEdit? $currentLang->flag_code: '';
                            $edit_fname = $isEdit? $currentLang->flag_name: '';
                            $edit_status = $isEdit? $currentLang->status: '';
                            $edit_rtl = $isEdit? $currentLang->rtl: 'no';
                            $edit_action = $isEdit? 'edit': 'new';
                            $languages = config('locales.languages');
                        @endphp
                        <input type="hidden" name="id" value="{{$edit_id}}"/>
                        <input type="hidden" name="action" value="{{$edit_action}}"/>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="language">{{__('Language')}}</label>
                                    <select name="language" id="language" class="form-control wide">
                                        <option value="">{{__('---- Select ----')}}</option>
                                        @if(!empty($languages))
                                            @foreach($languages as $key => $value)
                                                <option
                                                        {{$edit_lang == $key ? 'selected' : ''}} value="{{ $key }}">{{ $value . ' ('. $key .')' }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="w-100"></div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="flag_icon">{{__('Flag Icon')}}</label>
                                    <div class="flag-control">
                                        <input type="text" class="form-control gmz-icon-input"
                                               readonly
                                               id="flag_icon" name="flag_name"
                                               data-plugin="flagicon" value="{{$edit_fname}}"
                                               data-flags="{{ json_encode($countryData) }}"
                                               data-flag-url="{{asset('vendors/countries/flag/64x64/')}}"
                                               data-no-flags="{{__('No Flags')}}"
                                               placeholder="{{__('Flag Icon')}}">
                                        <input type="hidden" name="flag_code" value="{{$edit_ficon}}"
                                               class="flag-code"/>
                                        <div class="flag-display">
                                            @if(empty($edit_ficon))
                                                <span class="flag-icon"></span>
                                            @else
                                                <span
                                                        style="display: block"
                                                        data-code="{{$edit_ficon}}"
                                                        data-name="{{$edit_fname}}"
                                                        class="item-flag"
                                                        style="margin: 0px 5px;">
                                                        <img
                                                                src="{{asset('vendors/countries/flag/64x64/' . $edit_ficon . '.png')}}"/>

                                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">{{__('Name')}}</label>
                                    <input type="text" class="form-control gmz-validation" data-validation="required" id="name" name="name"
                                           value="{{$edit_name}}" placeholder="{{__('Display name')}}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="status">{{__('Right to Left')}}</label>
                                    @php
                                        admin_enqueue_styles('gmz-switches');
                                    @endphp
                                    <div>
                                        @php
                                            $checked = 'checked';
                                            if(empty($edit_rtl) || (!empty($edit_rtl) && $edit_rtl == 'no')){
                                                $checked = '';
                                            }
                                        @endphp
                                        <label class="gmz-switcher switch s-icons s-outline  s-outline-primary  mb-0">
                                            <input type="checkbox" name="rtl" {{$checked}} value="on">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="status">{{__('Status')}}</label>
                                    <div>
                                        @php
                                            $checked = 'checked';
                                            if(( !empty($edit_status) && $edit_status == 'off')){
                                                $checked = '';
                                            }
                                        @endphp
                                        <label class="gmz-switcher switch s-icons s-outline  s-outline-primary  mb-0">
                                            <input type="checkbox" name="status" {{$checked}} value="on">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-message"></div>
                        <button type="submit" class="btn btn-success mt-2">
                            @if(!$isEdit)
                                {{__('Add new')}}
                            @else
                                {{__('Edit')}}
                            @endif
                        </button>
                    </form>
                </div>
                <div class="col-lg-7">
                    <h6>{{__('All Languages')}}</h6>
                    <div class="table-responsive mb-4 mt-4">
                        <table class="multi-table table table-striped table-bordered table-hover non-hover w-100" data-plugin="footable"
                               data-sort="true"
                               data-sort-field="lang"
                               data-sort-action="sort-language"
                        >
                            <thead>
                            <tr>
                                <th>{{__('Language')}}</th>
                                <th data-breakpoints="xs">{{__('RLT')}}</th>
                                <th data-breakpoints="xs">{{__('Status')}}</th>
                                <th class="text-center">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (!$allLanguages->isEmpty())
                                @foreach ($allLanguages as $item)
                                    <tr data-lang="{{$item->code}}">
                                        <td class="align-middle">
                                            <img
                                                    src="{{asset('vendors/countries/flag/48x48/' . $item->flag_code . '.png')}}"/>
                                            {{ $item->name . ' ('. $item->code .')' }}
                                        </td>
                                        <td class="align-middle">
                                            {{$item->rtl == 'on' ? __('Yes') : __('No')}}
                                        </td>
                                        <td class="align-middle">
                                            @php
                                                $data = [
                                                    'languageID' => $item->id,
                                                    'languageHashing' => gmz_hashing($item->id),
                                                ];
                                            @endphp
                                            <label class="gmz-switcher gmz-switcher-action switch s-icons s-outline  s-outline-primary  mb-0"
                                                   data-params="{{base64_encode(json_encode($data))}}"
                                                   data-confirm="true" data-action="{{dashboard_url('change-language-status')}}">
                                                <input type="checkbox" value="on" @if( $item->status == 'on') checked @endif>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>

                                        <td class="text-center align-middle">
                                            <div class="dropdown custom-dropdown">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                                    @php
                                                        $url = dashboard_url('language');
                                                          $url = add_query_arg([
                                                              'action' => 'edit',
                                                              'id' => $item->id,
                                                          ], $url);
                                                    @endphp
                                                    <a href="{{$url}}" class="dropdown-item">{{__('Edit')}}</a>
                                                    <a class="dropdown-item text-danger gmz-link-action" href="javascript:void(0);" data-confirm="true" data-action="{{dashboard_url('delete-language')}}" data-params="{{base64_encode(json_encode($data))}}" data-remove-el="tr">{{__('Delete')}}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6">
                                        <h4 class="mt-3 text-center">{{__('No languages yet.')}}</h4>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


