<?php
if (empty($data)) {
   return false;
}
$date_range = $data['range'];
?>

<div class="widget widget-chart-one" id="widgetRevenue" data-json='{{json_encode($data['data'])}}'
     data-subtitle="{{__('Total Profit')}}" data-symbol='{{json_encode(get_symbol_currency())}}'>
    <div class="widget-heading">
        <h5 class="">{{__('Revenue')}}</h5>
        <ul class="tabs tab-pills text-right">
            @foreach($data['menu'] as $menu)
                <li>
                    <a href="javascript:void(0);" id="tb_{{$loop->iteration}}" class="tabmenu getChartData"
                       data-start="{{$menu['start']}}" data-end="{{$menu['end']}}">
                        {{__($menu['name'])}}
                    </a>
                </li>
            @endforeach
            <li class="dropdown">
                <a href="javascript:void(0);" id="searchRangeDate" class="tabmenu dropdown-toggle"
                   data-toggle="dropdown">
                    {{__('More')}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-chevron-down">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <div class="dropdown-menu position-absolute">
                    <div data-min="{{$date_range['min']}}" data-default="{{$date_range['default']}}"
                         data-max="{{$date_range['max']}}">
                        <input type="text" class="form-control mb-1" id="fStartDate" placeholder="Start Date">
                        <input type="text" class="form-control mb-1" id="fEndDate" placeholder="End Date">
                        <button href="javascript:void(0);" id="tb_100" class="btn btn-info w-100 getChartData"
                                data-start="{{$date_range['default']}}" data-end="{{$date_range['max']}}">
                            {{__('Filter')}}
                        </button>
                    </div>
                </div>
            </li>
        </ul>
    </div>

    <div class="widget-content">
        <div class="tabs tab-content">
            <div id="content_1" class="tabcontent">
                <div id="revenueMonthly"></div>
            </div>
        </div>
    </div>
</div>

