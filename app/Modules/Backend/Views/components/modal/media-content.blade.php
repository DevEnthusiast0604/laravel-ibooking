    @include('Backend::components.loader')
    <div class="modal-header">
        <h5 class="modal-title" id="newMediaModalLabel">{{__('Media Detail')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-6">
                <img src="{{$data['media_url']}}" alt="{{$data['media_name']}}"  class="img-fluid"/>
            </div>
            <div class="col-lg-6">
                <h4>{{$data['media_title']}}</h4>
                <ul>
                    <li>{{sprintf(__('ID: %s'), $data['id'])}}</li>
                    <li>{{sprintf(__('Size: %s KB'), number_format(($data['media_size']/1024), 2))}}</li>
                    <li>{{sprintf(__('Type: %s'), $data['media_type'])}}</li>
                    <li>{{sprintf(__('Author: %s'), get_user_name($data['author']))}}</li>
                    <li>{{sprintf(__('Created At: %s'), date(get_date_format(), strtotime($data['created_at'])))}}</li>
                </ul>
                <div class="form-group">
                    <input type="text" id="gmz-media-{{$data['id']}}" class="form-control" readonly value="{{$data['media_url']}}"/>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        @php
            $params = [
                'mediaID' => $data['id'],
                'mediaHashing' => gmz_hashing($data['id'])
            ];
        @endphp
        <a class="gmz-link-action btn btn-danger" href="javascript:void(0);" data-confirm="true" data-action="{{dashboard_url('delete-media-item')}}" data-params="{{base64_encode(json_encode($params))}}">{{__('Delete')}}</a>
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> {{__('Close')}}</button>
    </div>