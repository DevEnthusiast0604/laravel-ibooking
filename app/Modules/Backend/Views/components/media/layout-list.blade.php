@php
    admin_enqueue_styles([
        'gmz-datatables',
        'gmz-dt-multiple-tables',
        'footable'
    ]);
    admin_enqueue_scripts([
        'gmz-datatables',
        'footable'
    ]);
@endphp
<div class="gmz-all-media mt-3">
    @if(!$data->isEmpty())
        <div class="table-responsive">
            <table class="multi-table table table-striped table-bordered table-hover non-hover overflow-hidden w-100" data-plugin="footable">
                <thead>
                <tr>
                    <th>
                        <div class="checkbox checkbox-success gmz-check-all">
                            <input type="checkbox" id="gmz-checkbox-all">
                        </div>
                    </th>
                    <th>{{__('Name')}}</th>
                    <th data-breakpoints="xs sm">{{__('Size/Type')}}</th>
                    <th data-breakpoints="xs sm">{{__('Date')}}</th>
                    <th data-breakpoints="xs sm">{{__('Action')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data->items() as $key => $item)
                    <tr>
                        <td scope="row" class="align-middle">
                            <div class="checkbox  checkbox-success ">
                                <input type="checkbox" name="media_id[]" value="{{$item['id']}}" class="gmz-check-all-item" />
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                            @php
                                $params = [
                                   'media_id' => $item['id'],
                                   'media_hashing' => gmz_hashing($item['id'])
                               ];
                            @endphp
                            <a href="javascript:void(0)" class="gmz-open-modal" data-target="#gmz-media-item-modal" data-action="{{dashboard_url('get-media-detail')}}" data-params="{{ base64_encode(json_encode($params)) }}">
                            <img style="width: 100px; height: auto;" src="{{ $item['media_url'] }}" alt="{{ $item['media_description'] }}" class="img-fluid mr-3 mw-100">
                            </a>
                            <div class="">
                                <h6 class="mb-1">{{$item['media_title']}}</h6>
                                <p class="mb-0"><small><i>{{sprintf(__('Author: %s'), get_user_name($item['author']))}}</i></small></p>
                            </div>
                            </div>
                        </td>
                        <td>
                            {{number_format(($item['media_size']/1024), 2)}}/{{$item['media_type']}}
                        </td>
                        <td>
                            {{date(get_date_format(), strtotime($item['created_at']))}}
                        </td>

                        <td>
                            @php
                                $params = [
                                    'mediaID' => $item['id'],
                                    'mediaHashing' => gmz_hashing($item['id'])
                                ];
                            @endphp
                            <a class="btn btn-danger btn-sm gmz-link-action" href="javascript:void(0);" data-confirm="true" data-action="{{dashboard_url('delete-media-item')}}" data-params="{{base64_encode(json_encode($params))}}" data-remove-el="tr">{{__('Delete')}}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="gmz-pagination mt-4 pl-2 d-block">
                {!! $data->appends($_GET)->onEachSide(1)->links() !!}
            </div>
        </div>
    @else
        <span class="pl-2"><i>{{__('No data')}}</i></span>
    @endif
</div>