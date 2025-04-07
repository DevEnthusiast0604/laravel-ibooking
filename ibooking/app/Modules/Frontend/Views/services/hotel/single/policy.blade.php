@php
    $policies = maybe_unserialize($post['policy']);
@endphp
@if($enable_cancellation == 'on' || !empty($policies))
<section class="policy">
    <h2 class="section-title">{{__('Policies')}}</h2>
    <div class="section-content">
        @if($enable_cancellation == 'on')
            <div class="cancel-wrapper">
            <div class="cancel-day">
                {{sprintf(__('Customers can cancel this Hotel before %s day(s)'), $cancel_before)}}
            </div>
            @if(!empty($cancellation_detail))
                <div class="cancel-detail">
                    {{get_translate($cancellation_detail)}}
                </div>
            @endif
            </div>
        @endif


        @if(!empty($policies))
            <div class="hotel-policy">
                @foreach($policies as $k => $v)
                <div class="item">
                    <div class="label">
                        {{get_translate($v['title'])}}
                    </div>
                    <div class="value">
                        @php echo balance_tags(nl2br(get_translate($v['content']))) @endphp
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endif