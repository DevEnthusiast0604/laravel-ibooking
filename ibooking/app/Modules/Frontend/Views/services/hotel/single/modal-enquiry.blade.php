<div class="modal fade hotel-enquiry-modal" id="hotelEnquiryModal" tabindex="-1" aria-labelledby="hotelEnquiryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="gmz-form-action enquiry-form-single" action="{{ url('hotel-send-enquiry') }}" method="POST">
            <div class="modal-header">
                <h5 class="modal-title" id="hotelEnquiryModalLabel">{{__('ENQUIRY FORM')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <input type="hidden" name="post_id" value="{{$post['id']}}"/>
                    <input type="hidden" name="post_hashing" value="{{gmz_hashing($post['id'])}}"/>
                    @include('Frontend::components.loader')
                    <div class="form-group">
                        <label for="full-name">{{__('Full Name')}}<span class="required">*</span> </label>
                        <input type="text" name="full_name"  class="form-control gmz-validation" data-validation="required" id="full-name"/>
                    </div>
                    <div class="form-group">
                        <label for="email">{{__('Email')}}<span class="required">*</span></label>
                        <input type="text" name="email"  class="form-control gmz-validation" data-validation="required" id="email"/>
                    </div>
                    <div class="form-group">
                        <label for="content">{{__('Message')}}<span class="required">*</span> </label>
                        <textarea name="content" rows="4" class="form-control gmz-validation" data-validation="required" id="content"></textarea>
                    </div>
                    <div class="gmz-message"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('CLOSE')}}</button>
                <button type="submit" class="btn btn-primary">{{__('SUBMIT REQUEST')}}</button>
            </div>
            </form>
        </div>
    </div>
</div>