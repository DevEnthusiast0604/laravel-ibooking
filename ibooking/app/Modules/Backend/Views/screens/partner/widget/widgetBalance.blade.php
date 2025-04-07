<?php
if (empty($data)) {
   return false;
}
?>

<div class="widget widget-account-invoice-two" id="widgetBalance">
    <div class="widget-content">
        <div class="account-box">
            <div class="info">
                <h5 class="text-white">{{__("Balance")}}</h5>
                <p class="inv-balance">
                    {{convert_price($data['balance'])}}
                    <span class="inv-on-hold">{{__('On hold ')}}{{convert_price($data['on_hold'])}}</span>
                </p>
            </div>
            <div class="acc-action">
                <div class="">
                    <a href="{{dashboard_url('profile')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-credit-card">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                    </a>
                </div>
                <a href="{{dashboard_url('withdrawal')}}">{{__('Withdrawal')}}</a>
            </div>
        </div>
    </div>
</div>
