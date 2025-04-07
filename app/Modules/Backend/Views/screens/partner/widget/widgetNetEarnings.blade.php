<?php
    if (empty($data)){
       return false;
    }
?>
<div class="widget-one gmz-bg-orange" id="widgetNetEarnings" data-json="{{json_encode($data["data_chart"])}}" data-name="{{__('Sales')}}" data-symbol="{{json_encode(get_symbol_currency())}}">
    <div class="widget-content">
        <div class="w-numeric-value">
            <div class="w-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7" y2="7"></line></svg>
            </div>
            <div class="w-content">
                <span class="w-value">{{convert_price($data['total'])}}</span>
                <span class="w-numeric-title">{{__('Net Earnings')}}</span>
            </div>
        </div>
        <div class="w-chart">
            <div id="total-net-earnings"></div>
        </div>
    </div>
</div>