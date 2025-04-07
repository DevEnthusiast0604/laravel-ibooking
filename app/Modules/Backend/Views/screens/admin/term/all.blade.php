@extends('Backend::layouts.master')

@section('title', $taxonomy->taxonomy_title)

@php
    admin_enqueue_styles([
        'gmz-datatables',
        'gmz-dt-global',
        'gmz-dt-multiple-tables',
        'jquery-ui',
        'footable'
    ]);
    admin_enqueue_scripts([
        'gmz-datatables',
        'jquery-ui',
        'footable'
    ]);
    $fields_column = admin_config($taxonomy->taxonomy_name, 'term');

    foreach ($fields_column as $arr){
       if(isset($arr['type']) && $arr['type'] == 'location'){
          admin_enqueue_styles('mapbox-gl');
          admin_enqueue_styles('mapbox-gl-geocoder');
          admin_enqueue_scripts('mapbox-gl');
          admin_enqueue_scripts('mapbox-gl-geocoder');
          break;
       }
    }
@endphp

@section('content')

    @php
        $params = [
            'termID' => '',
            'termHashing' => gmz_hashing(''),
            'taxID' => $taxonomy->id,
            'taxHashing' => gmz_hashing($taxonomy->id),
            'taxName' => $taxonomy->taxonomy_name
        ];
        $columns = [];
        if(!empty($fields_column)){
            foreach ($fields_column as $col){
                $columns[$col['id']] = [
                    'label' => $col['label'],
                    'breakpoints' => isset($col['breakpoints']) ? $col['breakpoints'] : ''
                ];
            }
            if(isset($columns['parent'])){
                unset($columns['parent']);
            }
        }
    @endphp

    <div class="layout-top-spacing">
        <div class="statbox widget box box-shadow">

            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4>{{$taxonomy->taxonomy_title}}</h4>
                            <a href="{{dashboard_url('new-term/' . $taxonomy->taxonomy_name)}}" class="btn btn-success">{{__('Add New')}}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive mb-1 mt-4">
                @if($terms->total() > 0)
                    <table class="multi-table table table-striped table-bordered table-hover non-hover w-100"
                           data-plugin="footable">
                        <thead>
                        <tr>
                            @if(!empty($fields_column))
                                @foreach($columns as $key => $col)
                                    @if($key != 'term_image' && $key != 'term_location')
                                        <th @if(!empty($col['breakpoints'])) data-breakpoints="{{$col['breakpoints']}}" @endif>{{$col['label']}}</th>
                                    @endif
                                @endforeach
                            @endif
                            <th class="text-center">{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php recursive_term_html($params, $columns, $terms->items()) @endphp
                        </tbody>
                    </table>

                    <div class="gmz-pagination mt-4 d-block">
                        {!! $terms->links() !!}
                    </div>

                @else
                    <div class="alert alert-warning">{{__('No data')}}</div>
                @endif
            </div>

        </div>
    </div>
@stop

