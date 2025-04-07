@php
    $postType = $order['post_type'];
@endphp
<html>
    <head>
        <title>{{sprintf(__('Invoice - %s'), get_translate(get_option('site_name')))}}</title>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
        <style type="text/css">
            *{
                font-family: 'Quicksand', sans-serif;
                font-weight: 400;
                margin: 0;
                padding: 0;
            }
            html, body {
                background: #f0f0f0;
            }
            #gmz-invoice-wrapper {
                background: white;
                padding: 25px;
                max-width: 900px;
            }
            .invoice-header{
                border-bottom: 1px solid #dfdfdf;
                padding-bottom: 25px;
                margin-bottom: 25px;
            }
            .invoice-header table td{
                width: 50%;
            }
            .invoice-logo{
                margin-bottom: 40px;
                max-height: 60px;
                width: auto;
            }
            .invoice-logo-alt{
                margin-bottom: 40px;
                color: #1ea499;
                font-weight: 600;
                font-size: 35px;
            }
            .invoice-company{
                margin-bottom: 15px;
                font-weight: 600;
                font-size: 19px;
            }
            .invoice-address,
            .invoice-website {
                margin-bottom: 1px;
                font-weight: 400;
            }
            .invoice-website a{
                color: #000;
                font-weight: 400;
                text-decoration: none;
            }
            .invoice-title{
                font-size: 32px;
                font-weight: 600;
                margin-bottom: 5px;
            }
            .invoice-id,
            .invoice-date{
                margin-bottom: 5px;
            }
            .invoice-amount{
                display: inline-block;
                border: 1px solid #dfdfdf;
                padding: 7px 10px;
                border-radius: 5px;
                margin-top: 15px;
            }
            .invoice-amount .amount-label{
                font-weight: 600;
                display: block;
                text-align: center;
                font-size: 15px;
            }
            .invoice-amount .amount-value{
                font-weight: 600;
                display: block;
                text-align: center;
                font-size: 23px;
                margin-top: 3px;
            }
            .invoice-customer{
                border-bottom: 1px solid #dfdfdf;
                padding-bottom: 25px;
                margin-bottom: 25px;
            }
            .invoice-customer h5{
                font-weight: 600;
                font-size: 17px;
                margin-bottom: 14px;
            }
            .invoice-customer table{
                width: 100%;
            }
            .invoice-customer table tr td{
                padding: 6px 0;
            }
            .invoice-customer table tr td:nth-child(2){
                font-weight: 600;
            }
            .invoice-info h5{
                font-weight: 600;
                font-size: 17px;
                margin-bottom: 14px;
            }
            .invoice-info table{
                width: 100%;
            }
            .invoice-info table tr td{
                padding: 6px 0;
            }
            .invoice-info table tr td a{
                text-decoration: none;
                color: #000;
                font-weight: 600;
                font-size: 17px;
            }
            .invoice-info table tr td span{
                font-style: italic;
                font-size: 15px;
            }
            .invoice-info table tr.invoice-price td{
                padding-top: 15px;
            }
            .invoice-info table tr.invoice-total-price td{
                border-top: 1px dashed #dfdfdf;
                padding-top: 12px;
                font-weight: 600;
            }
            .invoice-info table tr.invoice-total-price td:nth-child(2){
                color: #cc0000;
                font-size: 25px;
            }
            .invoice-info table tr.invoice-before-total-price td{
                padding-bottom: 15px;
            }
            .invoice-info table tr td:nth-child(2){
                font-weight: 600;
                text-align: right;
            }
            .room-detail{
                margin-bottom: 15px;
            }
            .room-detail > div{
                font-weight: 600;
                margin-bottom: 5px;
            }
            .room-detail small{
                font-style: italic;
            }
            @media print {
                #gmz-invoice-wrapper {
                    margin: 0 auto 0 auto;
                }
            }
            @media screen{
                #gmz-invoice-wrapper {
                    margin: 40px auto 40px auto;
                }
            }
        </style>
        <script>
            window.print();
        </script>
    </head>
    <body>
    <div id="gmz-invoice-wrapper">
        <div class="invoice-header">
            @php
            $invoiceLogo = get_invoice_logo();
            $invoiceCompanyName = get_invoice_company_name();
            $invoiceAddress = get_invoice_address();
            $invoiceSiteName = get_option('site_name')
            @endphp
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        @if(!empty($invoiceLogo))
                            <img src="{{$invoiceLogo}}" alt="{{$invoiceCompanyName}}" class="invoice-logo">
                        @else
                            <h1 class="invoice-logo-alt">{{get_translate($invoiceSiteName)}}</h1>
                        @endif
                        <p class="invoice-company">{{$invoiceCompanyName}}</p>
                        <p class="invoice-address">{{$invoiceAddress}}</p>
                        <p class="invoice-website"><a href="{{url('/')}}">{{url('/')}}</a></p>
                    </td>
                    <td style="text-align: right">
                        <h2 class="invoice-title">{{__('INVOICE')}}</h2>
                        <p class="invoice-id">{{sprintf(__('Invoice #%s'), $order['sku'])}}</p>
                        <p class="invoice-date">{{sprintf(__('Created: %s'), date(get_date_format(), strtotime($order['created_at'])))}}</p>
                        <p class="invoice-amount">
                            <span class="amount-label">{{__('AMOUNT')}}</span>
                            <span class="amount-value">{{convert_price($order['total'])}}</span>
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        <div class="invoice-customer">
            <h5>{{__('BILLING TO')}}</h5>
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td>{{__('Name')}}</td>
                    <td style="text-align: right">{{$order['first_name'] . ' ' . $order['last_name']}}</td>
                </tr>
                <tr>
                    <td>{{__('Email')}}</td>
                    <td style="text-align: right">{{esc_html($order['email'])}}</td>
                </tr>
                <tr>
                    <td>{{__('Phone')}}</td>
                    <td style="text-align: right">{{esc_html($order['phone'])}}</td>
                </tr>
                @if(!empty($order['address']))
                    <tr>
                        <td>{{__('Address')}}</td>
                        <td style="text-align: right">{{esc_html($order['address'])}}</td>
                    </tr>
                @endif
                @if(!empty($order['postcode']))
                    <tr>
                        <td>{{__('Postcode')}}</td>
                        <td style="text-align: right">{{esc_html($order['postcode'])}}</td>
                    </tr>
                @endif
            </table>
        </div>
        <div class="invoice-info">
            @php
                $checkoutData = json_decode($order['checkout_data'],true);
                $currency = json_decode($order['currency'],true);
                $post = get_post($order['post_id'],$checkoutData['post_type']);
                $gateway = Gateway::inst()->getGateway($order['payment_type']);
                $extras = [];
                if(isset($checkoutData['cart_data']['extras'])){
                    $extras = $checkoutData['cart_data']['extras'];
                }else{
                    if(isset($checkoutData['cart_data']['extra_data'])){
                        $extras = $checkoutData['cart_data']['extra_data'];
                    }
                }
            @endphp
            <h5>{{__('BOOKING DETAIL')}}</h5>
            <table cellspacing="0" cellpadding="0">
                <tbody>
                    @include('Plugin.Invoice::items.' . $postType)
                    <tr>
                        <td class="label">{{__('Payment Method')}}</td>
                        <td class="val">{{$gateway->getName()}}</td>
                    </tr>
                    <tr>
                        <td class="label">{{__('Payment Status')}}</td>
                        <td class="val">{!! the_paid($order['payment_status']) !!}</td>
                    </tr>
                    <tr class="invoice-price">
                        <td class="label">{{__('Base Price')}}</td>
                        <td class="val">{{convert_price($checkoutData['base_price'])}}</td>
                    </tr>
                    @if($order['post_type'] == GMZ_SERVICE_CAR)
                        @php
                            $equipments = $checkoutData['cart_data']['equipment_data'];
                            $insurances = $checkoutData['cart_data']['insurance_data'];
                        @endphp
                        @if(!empty($equipments))
                            <tr>
                                <td class="label">{{__('Equipment Price')}}</td>
                                <td class="val">{{convert_price($checkoutData['equipment_price'])}}</td>
                            </tr>
                        @endif
                        @if(!empty($insurances))
                            <tr>
                                <td class="label">{{__('Insurance Price')}}</td>
                                <td class="val">{{convert_price($checkoutData['insurance_price'])}}</td>
                            </tr>
                        @endif
                    @endif
                    @if(!empty($extras) && $extras != '[]')
                        <tr>
                            <td class="label">{{__('Extra Price')}}</td>
                            <td class="val">{{convert_price($checkoutData['extra_price'])}}</td>
                        </tr>
                    @endif
                    @if(!empty($checkoutData['coupon']))
                        <tr>
                            <td class="label">{{__('Coupon')}} ({{$checkoutData['coupon']}})</td>
                            <td class="val">-{{$checkoutData['coupon_percent']}}%</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="label">{{__('Sub Total')}}</td>
                        <td class="val">{{convert_price($checkoutData['sub_total'])}}</td>
                    </tr>
                    @if(!empty($checkoutData['tax']['included']) && !empty($checkoutData['tax']['percent']))
                        <tr class="invoice-before-total-price">
                            <td class="label">
                                {{__('Tax')}}
                                @if($checkoutData['tax']['included'] == 'on')
                                    <small>{{__('(included)')}}</small>
                                @endif
                            </td>
                            <td class="val">{{$checkoutData['tax']['percent']}}%</td>
                        </tr>
                    @endif
                    <tr class="invoice-total-price">
                        <td class="label">{{__('Total Amount')}}</td>
                        <td class="val">{{convert_price($checkoutData['total'])}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </body>
</html>