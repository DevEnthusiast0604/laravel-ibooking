@if(empty($data['slot']) || empty($data['agent']))
  <div class="alert alert-warning mb-0 mt-2">
      {{__('This service could not be used')}}
  </div>
@else
<form class="gmz-form-action booking-form-single" action="{{ url('beauty-add-cart') }}" method="POST">

    <input type="hidden" name="post_id" value="{{$post['id']}}"/>
    <input type="hidden" name="post_hashing" value="{{gmz_hashing($post['id'])}}"/>
    <div class="booking-slot">
        <label for="beautyBookingForm__slot">{{__('Slot')}}</label>
        @if($data['slot'])
        <select class="form-control" name="check_in" id="beautyBookingForm__slot">
            @foreach($data['slot'] as $value)
                <option value="{{$value['start']}}" data-agent="{{base64_encode(json_encode($value["agent"]))}}">
                    {{date("H:i",$value['start'])}}
                </option>
            @endforeach
        </select>
        @else
            <div class="alert alert-warning mb-0 mt-2">
                {{__('No available slots have been found')}}
            </div>
        @endif
    </div>
    <div class="booking-agent">
        <span class="beautyBookingForm__agent">{{__('Agent')}}</span>
        @if($data['agent'])
            @foreach($data['agent'] as $value)
                    <input type="radio" name="agent" id="agent_{{$value['id']}}" value="{{$value['id']}}" @if($loop->first) {{'checked'}} @endif>
                    <label for="agent_{{$value['id']}}" id="label_agent_{{$value['id']}}">
                        @if($value['thumbnail_id'])
                            <img src="{{get_attachment_url($value['thumbnail_id'], [100, 100])}}" class="img-fluid agent-thumbnail" alt="avatar">
                        @else
                            <img src="{{asset("public/images/noimage-avatar.jpg")}}" class="img-fluid agent-thumbnail" alt="avatar">
                        @endif
                        <div class="booking-agent__info">
                            <div class="card">
                                @if($value['thumbnail_id'])
                                    <img src="{{get_attachment_url($value['thumbnail_id'], [100, 100])}}" class="card-img-left" alt="avatar">
                                @else
                                    <img src="{{asset("public/images/noimage-avatar.jpg")}}" class="card-img-left" alt="avatar">
                                @endif
                                <div class="card-body">
                                    <h4 class="card-title">{{get_translate($value['post_title'])}}</h4>
                                    <p class="card-text">{!! get_translate($value['post_content']) !!}</p>
                                </div>
                            </div>

                        </div>
                    </label>
            @endforeach
        @else
            <div class="alert alert-warning mb-0 mt-2">
                {{__('No available agents have been found')}}
            </div>
        @endif
    </div>
    <div class="gmz-message"></div>
    <button type="submit" class="btn btn-primary btn-book-now">{{__('BOOK NOW')}}</button>
</form>
@endif
