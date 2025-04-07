<form class="gmz-form-action" action="{{$data['action']}}" method="POST" data-loader="body">
    @include('Backend::components.loader')
    <div class="modal-header">
        <h5 class="modal-title" id="newUserModalLabel">{{$data['title']}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="user_id" value="{{$data['user_id']}}" />
        @php
            $fields = admin_config('new-form-fields', 'user');
            $is_user = true;
        @endphp
        @include('Backend::settings.term')
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> {{__('Discard')}}</button>
        <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
    </div>
</form>