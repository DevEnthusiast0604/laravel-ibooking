@php
    $langs = get_languages(true);
    $current_session = get_current_language();
@endphp
@if(count($langs) > 1)
    @php
        $current_lang = $langs[0];
        foreach ($langs as $item){
            if(($item['code'] == $current_session) ){
                $current_lang = $item;
            }
        }
    @endphp
    <li class="nav-item dropdown language-dropdown more-dropdown">
        <div class="dropdown  custom-dropdown-icon">
            <a class="dropdown-toggle btn" href="javascript:void(0);" role="button" id="langDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ esc_attr(asset('vendors/countries/flag/32x32/' . $current_lang['flag_code'] . '.png')) }}" class="flag-width" alt="flag"><span>{{$current_lang['name']}}</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></a>

            <div class="dropdown-menu dropdown-menu-right animated fadeInUp" aria-labelledby="langDropdown">
                @foreach($langs as $item)
                    @if($item['code'] !== $current_lang['code'])
                    @php
                        $url = \Illuminate\Support\Facades\Request::fullUrl();
                        $url = add_query_arg('lang', $item['code'], $url);
                    @endphp
                    <a class="dropdown-item" href="{{ $url }}">
                        <img src="{{ esc_attr(asset('vendors/countries/flag/32x32/' . $item['flag_code'] . '.png')) }}" class="flag-width" alt="flag">
                        {{$item['name']}}
                    </a>
                    @endif
                @endforeach
            </div>
        </div>
    </li>
@endif