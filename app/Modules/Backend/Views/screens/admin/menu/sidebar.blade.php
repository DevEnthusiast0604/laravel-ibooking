<h5 class="header-title mb-0 font-weight-normal">{{__('Menu Items')}}</h5>
@php
    $items = ['page', 'post', 'hotel', 'car', 'apartment', 'space', 'tour', 'beauty', 'link', 'category', 'tag'];
@endphp
@foreach($items as $item)
    @include('Backend::screens.admin.menu.items.' . $item)
@endforeach