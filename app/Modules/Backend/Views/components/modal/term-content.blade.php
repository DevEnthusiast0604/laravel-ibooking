<form class="gmz-form-action form-translation gmz-modal-form" action="{{$data['action']}}" method="POST" data-loader="body">
    @include('Backend::components.loader')
    <div class="modal-header">
        <h5 class="modal-title" id="newTermModalLabel">{{$data['title']}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </div>
    <div class="modal-body">
        @php
            render_flag_option();
            $fields = admin_config($data['tax_name'], 'term');
        @endphp
        <input type="hidden" name="taxonomy_id" value="{{$data['tax_id']}}" />
        <input type="hidden" name="term_id" value="{{$data['term_id']}}" />
        <input type="hidden" name="fields" value="{{base64_encode(json_encode($fields))}}" />
        @include('Backend::settings.term')
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal" href="javascript:void(0);"><i class="flaticon-cancel-12"></i> {{__('Discard')}}</a>
        <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
    </div>
</form>