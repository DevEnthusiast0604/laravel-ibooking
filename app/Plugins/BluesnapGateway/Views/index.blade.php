<div class="pm-info">
    <div class="row">
        <div class="col-sm-6">
            <div class="col-card-info">
                <div class="form-group">
                    <label for="gmz_bluesnap_card_name">{{__('Name on the Card (*)')}}</label>
                    <div class="controls">
                        <input type="text" value="" class="form-control" name="gmz_bluesnap_card_name" id="gmz_bluesnap_card_name" placeholder="{{__('Card name')}}">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group second">
                        <label for="gmz_bluesnap_card_number">{{__('Card number (*)')}}</label>
                        <div class="controls">
                            <input type="text" value="" class="form-control" name="gmz_bluesnap_card_number" id="gmz_bluesnap_card_number" placeholder="{{__('Your card number')}}">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-control-wrap">
                                <label for="gmz_bluesnap_card_expiry_month">{{__('Month (*)')}}</label>
                                <select name="gmz_bluesnap_card_expiry_month" id="gmz_bluesnap_card_expiry_month" class="form-control app required">
                                    <optgroup label="{{__('Month')}}">
                                        <?php
                                        for($i=1;$i<=12;$i++){
                                            printf('<option value="%s">%s</option>',$i,$i);
                                        } ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-control-wrap">
                                <label for="gmz_bluesnap_card_expiry_year">{{__('Year (*)')}}</label>
                                <select name="gmz_bluesnap_card_expiry_year" id="gmz_bluesnap_card_expiry_year" class="form-control app required">
                                    <optgroup label="{{__('Year')}}">
                                        <?php
                                        $y=date('Y');
                                        for($i=date('Y');$i<$y+49;$i++){
                                            printf('<option value="%s">%s</option>',$i,$i);
                                        } ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group card-code">
                                <label for="gmz_bluesnap_card_code">{{__('CVV (*)')}}</label>
                                <div class="controls">
                                    <input type="text" value="" class="form-control" name="gmz_bluesnap_card_code" id="gmz_bluesnap_card_code">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $desc = get_translate(get_option('payment_'. $id .'_desc'));
@endphp
@if(!empty($desc))
    {!! $desc !!}
@endif
