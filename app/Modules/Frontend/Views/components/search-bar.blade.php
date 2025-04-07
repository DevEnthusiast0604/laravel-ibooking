<div class="results-count d-flex align-items-center justify-content-between">
    <div>
        {!! $search_str !!}
    </div>
    <div class="d-flex align-items-center justify-content-between">
        @if(isset($params['sort']))
        <div class="sort">
            <div class="dropdown">
                <button class="btn btn-link dropdown" type="button" id="dropdownMenuSort" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{__('Sort')}} <i class="fal fa-angle-down arrow"></i>
                </button>
                <div class="dropdown-menu sort-menu dropdown-menu-right" aria-labelledby="dropdownMenuSort">
                    <div class="sort-title">
                        <h3>{{__('SORT BY')}}</h3>
                    </div>
                    <div class="sort-item">
                        <label>
                            <input class="service-sort" type="radio" name="sort" data-value="new" value="new" {{(($params['sort'] == 'new') ? 'checked="checked"' : '')}}>
                            {{__('New')}}
                        </label>
                    </div>
                    <div class="sort-item">
                        <span class="title">{{__('Price')}}</span>
                        <label>
                            <input class="service-sort" type="radio" name="sort" data-value="price_asc" value="price_asc" {{(($params['sort'] == 'price_asc') ? 'checked="checked"' : '')}}>
                            {{__('Low to High')}}
                        </label>
                        <label>
                            <input class="service-sort" type="radio" name="sort" data-value="price_desc" value="price_desc" {{(($params['sort'] == 'price_desc') ? 'checked="checked"' : '')}}>
                            {{__('High to Low')}}
                        </label>
                    </div>
                    <div class="sort-item">
                        <span class="title">{{__('Name')}}</span>
                        <label>
                            <input class="service-sort" type="radio" name="sort" data-value="name_a_z" value="name_a_z" {{(($params['sort'] == 'name_a_z') ? 'checked="checked"' : '')}}>
                            {{__('A - Z')}}
                        </label>
                        <label>
                            <input class="service-sort" type="radio" name="sort" data-value="name_z_a" value="name_z_a" {{(($params['sort'] == 'name_z_a') ? 'checked="checked"' : '')}}>
                            {{__('Z - A')}}
                        </label>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="layout">
            <a class="layout-item {{(($params['layout'] == 'list') ? 'active' : '')}}" data-layout="list" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="{{__('List View')}}">
                <i class="fal fa-list-alt"></i>
            </a>
            <a class="layout-item {{(($params['layout'] == 'grid') ? 'active' : '')}}" data-layout="grid" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="{{__('Grid View')}}">
                <i class="fal fa-th"></i>
            </a>
        </div>
    </div>
</div>