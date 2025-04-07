<form class="gmz-form-action form-translation gmz-payment-form" action="{{$data['action']}}" method="POST" data-loader="body">
    @include('Backend::components.loader')
    <input type="hidden" name="payment_structure" value=""/>
    <div class="modal-header">
        <h5 class="modal-title" id="newTermModalLabel">{{$data['title']}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </div>
    <div class="modal-body">
        @if(!empty($data['payments']))
        <div class="gmz-list-payment-box">
            <ol class="sortable">
                @foreach($data['payments'] as $key => $val)
                    <li id="{{$val['id']}}">
                        <div class="item">
                            <div class="item-header d-flex align-items-center justify-content-between">
                                <span class="name">{{$val['heading']}}</span>
                                {!! get_icon('icon_system_sort', '', '15', '15') !!}
                            </div>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
        @endif
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal" href="javascript:void(0);"><i class="flaticon-cancel-12"></i> {{__('Close')}}</a>
        <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
    </div>
</form>