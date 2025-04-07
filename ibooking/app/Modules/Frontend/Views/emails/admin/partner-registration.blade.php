@php
    $main_color = get_option('main_color', '#1ea69a');
    $css = '
           .text-center{
               text-align: center;
           }
           .gmz-email-wrapper{
               width: 95%;
               max-width: 650px;
               margin: 20px auto;
               border: 1px solid #EEE;
           }
           .gmz-email-wrapper .email-header{
                background: ' . $main_color . ';
                padding: 15px 20px;
           }
           .gmz-email-wrapper .email-header table{
               width: 100%;
               border: none;
               table-layout: fixed;
           }
           .gmz-email-wrapper .email-header .logo{
              width: 60px;
              height: auto;
           }
           .gmz-email-wrapper .email-header .description{
               font-size: 18px;
               text-transform: uppercase;
               letter-spacing: 1px;
               margin-bottom: 0;
               margin-top: 0;
               color: #fff;
           }
           .email-content{
               padding: 20px 20px 30px 20px;
           }

           .booking-detail{
               margin-top: 30px;
               padding: 10px 20px;
               border: 1px solid #EEE;
           }
           .order-detail .item{
               padding-top: 15px;
               padding-bottom: 15px;
               border-bottom: 1px solid #EEE;
               display: flex;
           }
           .order-detail .item .title{
               display: inline-block;
               font-size: 15px;
               width: 35%;
           }
           .order-detail .item .info{
               display: inline-block;
               font-size: 15px;
               font-weight: bold;
               width: 65%;
           }
           .order-detail .client-info .info{
               font-weight: normal;
           }
           .order-detail .client-info .info p{
               margin-top: 0;
           }
           .btn-primary{
               display: inline-block;
               padding: 10px 20px;
               border: none;
               background: ' . $main_color . ';
               color: #FFF;
               text-align: center;
               text-decoration: none;
           }
           .conf-button{
               margin-top: 30px;
               text-transform: uppercase;
               text-decoration: none;
           }

           .email-action{
                margin-top: 20px;
                text-align: center;
           }

           .email-footer{
               border-top: 1px solid #EEE;
               padding: 30px 0;
               background: #dfdfdf;
               text-align: center;
           }
       ';
   ob_start();
@endphp
<div class="gmz-email-wrapper">
    <div class="email-header">
        <table>
            <tr>
                <td style="text-align: left">
                    @php
                        $logo = get_option('logo_email');
                        $logo_url = get_attachment_url($logo);
                        $site_name = get_translate(get_option('site_name'));
                        $site_desc = get_translate(get_option('site_description'));
                    @endphp
                    <a href="{{ url('/') }}" target="_blank">
                        <img src="{{ $logo_url }}" alt="{{ $site_name }}" class="logo">
                    </a>
                </td>
                <td style="text-align: right">
                    <h4 class="description">{{ $site_name }}</h4>
                </td>
            </tr>
        </table>
    </div>
    <div class="email-content">
        <p>{{__('Hello')}} <strong>{{$name}}</strong>,</p>
        <p><b>{{__('New partner registration request')}}</b></p>
        <div class="order-detail">
            <div class="item">
                <span class="title">{{__('First Name')}}</span>
                <span class="info">{{ esc_html($post_data['first_name']) }}</span>
            </div>
            <div class="item">
                <span class="title">{{__('Last Name')}}</span>
                <span class="info">{{ esc_html($post_data['last_name']) }}</span>
            </div>
            <div class="item">
                <span class="title">{{__('Email')}}</span>
                <span class="info">{{esc_html($post_data['email'])}}</span>
            </div>
            <div class="item">
                <span class="title">{{__('Address')}}</span>
                <span class="info">{{esc_html($post_data['address'])}}</span>
            </div>
        </div>
        <div class="email-action">
            <a class="btn-primary" target="_blank" href="{{dashboard_url('partner-request')}}">{{__('Manage Partner')}}</a>
        </div>
    </div>
    <div class="email-footer">
        &copy; {{ date('Y') }} - {{ $site_desc }}
    </div>
</div>
@php
    $content = @ob_get_clean();
    $render = new Emogrifier();
    $render->setHtml($content);
    $render->setCss($css);
    $mergedHtml = $render->emogrify();
    $mergedHtml = str_replace('<!DOCTYPE html>', '', $mergedHtml);
    unset($render);
@endphp
{!! balance_tags($mergedHtml) !!}