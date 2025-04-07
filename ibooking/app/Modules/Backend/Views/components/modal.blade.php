@php
    admin_enqueue_styles('gmz-dropzone');
    admin_enqueue_scripts('gmz-dropzone');
@endphp
<div class="modal fade gmz-media-modal" id="gmzMediaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        @include('Backend::components.loader')
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{__('Media')}}
                    <button type="button" class="btn btn-primary btn-sm btn-addnew">{{__('Add new')}}</button>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
                <div id="gmz-media-add-new" class="gmz-media-upload-area">
                    <form action="{{ dashboard_url('upload-new-media') }}" method="post" class="gmz-dropzone"
                          id="gmz-upload-form" enctype="multipart/form-data">
                        @include('Backend::components.loader')
                        <input type="hidden" name="is_modal" value="1" />
                        <div class="fallback">
                            <input name="file" type="file" multiple/>
                        </div>
                        <div class="dz-message text-center needsclick">
                            <i data-feather="upload-cloud"></i>
                            <h3>{{__('Drop files here or click to upload.')}}</h3>
                            <p class="text-muted">
                                <span>{{__('Only JPG, PNG, JPEG, SVG, GIF files types are supported.')}}</span>
                                <span>{{sprintf(__('Maximum file size is %s MB.'), admin_config('max_file_size'))}}</span>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <button type="button" class="btn btn-danger btn-delete ml-2" data-url="{{dashboard_url('bulk-delete-media-item')}}">{{__('Delete')}}</button>
                    <span>&nbsp;</span>
                    <div class="mr-4">
                        <button class="btn btn-close" data-dismiss="modal"><i class="flaticon-cancel-12"></i> {{__('Close')}}</button>
                        <button type="button" class="btn btn-primary btn-select">{{__('Select')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>