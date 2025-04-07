<div class="widget-content widget-content-area icon-pill" id="gmz-settings-page">
    @include('Backend::components.loader')
    <ul class="nav nav-pills mb-3 mt-3" id="icon-pills-tab" role="tablist">
        @foreach($settings['sections'] as $key => $item)
            <li class="nav-item">
                <a class="nav-link @if($key == 0) active @endif" id="icon-pills-{{$item['id']}}-tab"
                   data-toggle="pill" data-target="#gmz-{{$item['id']}}" href="javascript:void(0)" role="tab"
                   aria-controls="icon-pills-{{$item['id']}}"
                   aria-selected="@if($key == 0) true @else false @endif">
                    {{$item['label']}}
                </a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content" id="icon-pills-tabContent">
        @foreach($settings['sections'] as $key => $item)
            <div class="tab-pane fade show @if($key == 0) active @endif" id="gmz-{{$item['id']}}"
                 role="tabpanel" aria-labelledby="icon-pills-{{$item['id']}}">
                <form class="gmz-form-action form-translation" action="{{dashboard_url('save-settings')}}" method="POST" data-loader="body" enctype="multipart/form-data">
                    @php
                        if (!isset($item['translation']) || (isset($item['translation']) && $item['translation'])) {
                            render_flag_option();
                        }
                        $options = [];
                    @endphp

                    <div class="row">
                    @foreach($settings['fields'] as $_key => $_item)
                        @if($_item['section'] == $item['id'])
                            @if(isset($_item['tabs']) && !empty($_item['tabs']))
                                <div class="col-12">
                                @php
                                    $_item['tabs'] = Eventy::filter('gmz_settings_' . $_item['section'] . '_tabs', $_item['tabs']);
                                    if(!is_array($_item['tabs']) && $_item['tabs'] == 'payment_settings'){
    $_item['tabs'] = BaseGateway::inst()->getPaymentSettings();
}
                                @endphp
                                <ul class="nav nav-tabs  mb-3 mt-3" id="{{$_item['id']}}SettingsTab" role="tablist">
                                    @foreach($_item['tabs'] as $val_tab)
                                    <li class="nav-item">
                                        <a class="nav-link @if($loop->index == 0) active @endif" id="{{$val_tab['id']}}-tab" data-toggle="tab" href="#{{$val_tab['id']}}" role="tab" aria-controls="{{$val_tab['id']}}" aria-selected="true">{{$val_tab['heading']}}</h6></a>
                                    </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content" id="{{$_item['id']}}SettingsTabContent">
                                    @foreach($_item['tabs'] as $val_tab)
                                        <div class="tab-pane fade @if($loop->index == 0) show active @endif" id="{{$val_tab['id']}}" role="tabpanel" aria-labelledby="{{$val_tab['id']}}-tab">
                                            <div class="row">
                                            @foreach($val_tab['fields'] as $val_tab_field)
                                                @if($val_tab['id'] == $val_tab_field['tab'])
                                                    @php
                                                        $val_tab_field = merge_option_values($settings_db, $val_tab_field);
                                                    @endphp

                                                        @include('Backend::settings.fields.render', [
                                                            'field' => $val_tab_field
                                                        ])

                                                    @php
                                                        $options[] = $val_tab_field;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                </div>
                            @else
                                @php
                                    $_item = merge_option_values($settings_db, $_item);
                                @endphp

                                    @include('Backend::settings.fields.render', [
                                        'field' => $_item
                                    ])

                                @php
                                    $options[] = $_item;
                                @endphp
                            @endif
                        @endif
                    @endforeach
                    </div>
                    <hr/>
                    <input type="hidden" name="options" value="{{base64_encode(json_encode($options))}}" />
                    <button type="submit" class="btn btn-success mt-2">{{__('Save Changes')}}</button>
                </form>
            </div>
        @endforeach
    </div>
</div>

@php
    admin_enqueue_scripts('jquery-ui');
    admin_enqueue_styles('jquery-ui');
    admin_enqueue_scripts('nested-sort-js');
@endphp
@include('Backend::components.modal.payment')
@include('Backend::components.modal.checking-email')