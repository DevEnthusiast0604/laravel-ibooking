<div class="table-filter mt-3">
    <form id="form_filter" action="{{current_url()}}" method="get">
        @php
            $get_data = request()->all();
            $post_type = request()->route()->parameters();
        @endphp
        @foreach($get_data as $key => $value)
            @continue(in_array($key,['filter_status','_token','search'],true))
            <input type="hidden" name="{{$key}}" value="{{$value}}" />
        @endforeach
        <div class="form-row">
            <div class="col mb-2">
                <div class="filter-action pl-2" id="menu_post_type" data-active="{{$post_type['post_type']}}">
                    @php
                        $services = get_services_enabled();
                    @endphp
                    @if(count($services) == 1)
                        <a href="{{dashboard_url("order/$services[0]/")}}" id="post_type_{{$services[0]}}" class="mr-2">{{__(ucwords($services[0]))}}</a>
                    @else
                        <a href="{{dashboard_url("order/all/")}}" id="post_type_all" class="mr-2">{{__("All")}}</a>
                        @foreach($services as $service)
                            <a href="{{dashboard_url("order/$service/")}}" id="post_type_{{$service}}" class="mr-2">{{__(ucwords($service))}}</a>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                @php
                    $search_value = request()->get('search', null);
                @endphp
                <div class="input-group input-group-sm">
                    <input class="form-control bg-light border-light" value="{{$search_value}}" type="text" name="search" placeholder="Search..." autocomplete="off">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">{{__('search')}}</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                @php
                    $filter_status  = request()->get('filter_status', 'none');
                @endphp

                <div class="input-group input-group-sm">
                    <select name="filter_status" id="filter_status" class="custom-select bg-light border-light">
                        <option value="none" {{selected('none', $filter_status)}}>{{__('Filter by Status')}}</option>
                        <option value="refund_request" {{selected('refund_request', $filter_status)}}>{{__('Refund request')}}</option>
                        <option value="payment_confirmation" {{selected('payment_confirmation', $filter_status)}}>{{__('Payment Confirmation')}}</option>
                        <option value="unfinished" {{selected('unfinished', $filter_status)}}>{{__('Unfinished')}}</option>
                        <option value="{{GMZ_STATUS_COMPLETE}}" {{selected(GMZ_STATUS_COMPLETE, $filter_status)}}>{{__('Complete')}}</option>
                        <option value="{{GMZ_STATUS_CANCELLED}}" {{selected(GMZ_STATUS_CANCELLED, $filter_status)}}>{{__('Cancelled')}}</option>
                        <option value="{{GMZ_STATUS_REFUNDED}}" {{selected(GMZ_STATUS_REFUNDED, $filter_status)}}>{{__('Refunded')}}</option>
                        <option value="{{GMZ_STATUS_INCOMPLETE}}" {{selected(GMZ_STATUS_INCOMPLETE, $filter_status)}}>{{__('Incomplete')}}</option>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">{{__('Filter')}}</button>
                    </div>
                </div>
            </div>
            @if(!empty($get_data['search']) || !empty($get_data['filter_status']))
                <a class="btn btn-sm btn-danger bs-tooltip" title="{{__("Clear Filter")}}" href="{{dashboard_url("order/".$post_type['post_type']."/")}}"><i class="fas fa-times mr-1"></i>{{__("Clear")}}</a>
            @endif
        </div>
    </form>
</div>
