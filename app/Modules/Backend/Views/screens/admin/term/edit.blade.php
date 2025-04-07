@extends('Backend::layouts.master')

@section('title', __(sprintf('Edit %s', $taxonomy->taxonomy_title)))

@section('content')

    <div class="layout-top-spacing">
        <div class="statbox widget box box-shadow">

            <div class="widget-header p-0 mb-4">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4>{{__(sprintf('Edit %s', $taxonomy->taxonomy_title))}}</h4>
                            <a href="{{dashboard_url('term/' . $data['tax_name'])}}" class="btn btn-dark">{{__('Back to all')}}</a>
                        </div>
                    </div>
                </div>
            </div>

            <form class="gmz-form-action form-translation" action="{{$data['action']}}" method="POST" data-loader="body" enctype="multipart/form-data">
                @include('Backend::components.loader')
                @php
                    render_flag_option();
                    $fields = admin_config($data['tax_name'], 'term');
                @endphp
                <input type="hidden" name="taxonomy_id" value="{{$data['tax_id']}}" />
                <input type="hidden" name="taxonomy_hashing" value="{{gmz_hashing($data['tax_id'])}}" />
                <input type="hidden" name="taxonomy_name" value="{{$data['tax_name']}}" />
                <input type="hidden" name="taxonomy_name_hashing" value="{{gmz_hashing($data['tax_name'])}}" />
                <input type="hidden" name="term_id" value="{{$data['term_id']}}" />
                <input type="hidden" name="fields" value="{{base64_encode(json_encode($fields))}}" />
                @include('Backend::settings.term')
                <div class="d-flex align-items-center justify-content-end">
                    <button type="submit" class="btn btn-primary mr-2">{{__('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
@stop

