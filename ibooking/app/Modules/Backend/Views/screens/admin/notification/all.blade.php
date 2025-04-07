    @extends('Backend::layouts.master')

@section('title', __('All Notifications'))

@php
    admin_enqueue_styles([
        'gmz-datatables',
        'gmz-dt-global',
        'gmz-dt-multiple-tables',
        'footable'
    ]);
    admin_enqueue_scripts([
        'gmz-datatables',
        'footable'
    ]);
@endphp

@section('content')

    <div class="layout-top-spacing">
        <div class="statbox widget box box-shadow">

            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4>{{__('All Notifications')}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive mb-4 mt-4">
                @if($allPosts->total() > 0)
                <table class="multi-table table table-striped table-bordered table-hover non-hover  w-100" data-plugin="footable">
                    <thead>
                    <tr>
                        <th>{{__('Title')}}</th>
                        <th data-breakpoints="xs sm">{{__('Content')}}</th>
                        <th data-breakpoints="xs sm">{{__('Type')}}</th>
                        <th data-breakpoints="xs sm">{{__('Date')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($allPosts->items() as $key => $item)
                    <tr>
                        <td>
                            <b>{{$item['title']}}</b>
                        </td>
                        <td>
                           {{$item['message']}}
                        </td>
                        <td>
                           {{ucfirst($item['type'])}}
                        </td>
                        <td>{{date(get_date_format(), strtotime($item['created_at']))}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="gmz-pagination">
                    {!! $allPosts->links() !!}
                </div>

                @else
                    <div class="alert alert-warning">{{__('No data')}}</div>
                @endif
            </div>



        </div>
    </div>
@stop

