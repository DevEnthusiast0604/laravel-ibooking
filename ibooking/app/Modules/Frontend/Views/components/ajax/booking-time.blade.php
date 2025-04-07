<div class="booking-time">
    <label>{{__('Time')}}</label>
    <div class="booking-time__start">
        <div class="value">
            <select class="form-control" name="startTime">
                @if(!empty($list_times))
                    @foreach($list_times as $ktime => $vtime)
                        <option value="{{$ktime}}">{{$vtime}}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="booking-time__end">
        <div class="value">
            <select class="form-control" name="endTime" data-origin-text="{{__('End Time')}}">
                <option value="">{{__('End Time')}}</option>
            </select>
        </div>
    </div>
</div>
