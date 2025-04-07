{{--@push('scripts')--}}
@foreach($scripts as $key => $val)
    @if($val['queue'])
        @php
            $v = '';
            if(!empty($val['v'])){
                $v = '?v=' . $val['v'];
            }
        @endphp
        <script src="{{$val['url'] . $v}}"></script>
    @endif
@endforeach
{{--@endpush--}}
