@php
    if(empty($data) || $data->isEmpty()){
      return false;
    }

@endphp
    <div class="widget widget-table-one h-100">
        <div class="widget-heading">
            <h5 class="">{{__('Transactions')}}</h5>
        </div>
        <div class="widget-content gmz-scroll-content" id="widgetTransactionsContent">

            @foreach($data as $value)

                <?php $net_income = $value['total'] * ((100 - $value['commission'])/100); ?>
                @if($value['status'] == GMZ_STATUS_REFUNDED)
                    <div class="transactions-list">
                        <div class="t-item">
                            <div class="t-company-name">
                                <div class="t-icon">
                                    <div class="icon icon-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-down"><polyline points="7 13 12 18 17 13"></polyline><polyline points="7 6 12 11 17 6"></polyline></svg>
                                    </div>
                                </div>
                                <div class="t-name">
                                    <h4> #{{$value['sku']}} </h4>
                                    <p class="meta-date"> {{date(get_date_format(true), strtotime($value['updated_at']))}} </p>
                                </div>
                            </div>
                            <div class="t-rate rate-dec">
                                <p><span> - {{convert_price($net_income)}}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke - width="2" stroke - linecap="round" stroke -
                                         linejoin="round" class="feather feather-arrow-down">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <polyline points="19 12 12 19 5 12"></polyline>
                                    </svg>
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="transactions-list">
                        <div class="t-item">
                            <div class="t-company-name">
                                <div class="t-icon">
                                    <div class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-up"><polyline points="17 11 12 6 7 11"></polyline><polyline points="17 18 12 13 7 18"></polyline></svg>
                                    </div>
                                </div>
                                <div class="t-name">
                                    <h4> {{$value['sku']}} </h4>
                                    <p class="meta-date"> {{date(get_date_format(true), strtotime($value['updated_at']))}} </p>
                                </div>
                            </div>
                            <div class="t-rate rate-inc">
                                <p><span> + {{convert_price($net_income)}} </span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke - width="2" stroke - linecap="round" stroke -
                                         linejoin="round" class="feather feather-arrow-up">
                                        <line x1="12" y1="19" x2="12" y2="5"></line>
                                        <polyline points="5 12 12 5 19 12"></polyline>
                                    </svg>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>