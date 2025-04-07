<?php
    if(empty($data)){
        return false;
    }
    $wallet = $data['wallet'];
?>
<div class="widget widget-one" id="widgetIncomeStatistics" data-json="{{json_encode($data)}}" data-symbol="{{json_encode(get_symbol_currency())}}">
    <div class="widget-heading">
        <h6 class="">{{__('Statistics')}}</h6>
    </div>
    <div class="w-chart">
        <div class="w-chart-section">
            <div class="w-detail">
                <p class="w-title">{{__('Total')}}</p>
                <p class="w-stats">{{convert_price($wallet['total'])}}</p>
            </div>
            <div class="w-chart-render-one">
                <div id="total-earnings"></div>
            </div>
        </div>

        <div class="w-chart-section">
            <div class="w-detail">
                <p class="w-title">{{__('Net Earnings')}}</p>
                <p class="w-stats">{{convert_price($wallet['net_earnings'])}}</p>
            </div>
            <div class="w-chart-render-one">
                <div id="net-earnings"></div>
            </div>
        </div>
    </div>
</div>