<div class="gmz-all-media mt-3">
    @if(!$data->isEmpty())
        <div class="gmz-all-media-render relative">
            <div class="render">
                @foreach($data->items() as $item)
                    @include('Backend::components.media.item', ['attachment' => $item])
                @endforeach
            </div>
        </div>

        <div class="gmz-pagination mt-4 pl-2 d-block">
            {!! $data->appends($_GET)->onEachSide(1)->links() !!}
        </div>
    @else
        <span class="pl-2"><i>{{__('No data')}}</i></span>
    @endif
</div>