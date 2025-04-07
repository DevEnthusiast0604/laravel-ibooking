<div class="row">
    @foreach($posts as $item)
        <div class="col-lg-4 col-md-4 col-sm-12">
            @include('Frontend::services.apartment.items.grid-item')
        </div>
    @endforeach
</div>
<div class="pagination-wrapper">{{$posts->withQueryString()->links()}}</div>