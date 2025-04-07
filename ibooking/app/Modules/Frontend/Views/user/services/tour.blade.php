<div class="row">
    @foreach($posts as $item)
        <div class="col-lg-6 col-md-6 col-sm-12">
            @include('Frontend::services.tour.items.grid-item')
        </div>
    @endforeach
</div>
<div class="pagination-wrapper">{{$posts->withQueryString()->links()}}</div>