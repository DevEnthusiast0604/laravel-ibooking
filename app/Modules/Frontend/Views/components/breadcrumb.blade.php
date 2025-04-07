<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="{{url('/')}}">{{__('Home')}}</a></li>
            @if($post_type == 'car')
                <li><a href="{{url('car-search')}}">{{__('Car')}}</a></li>
            @endif
            @if($post_type == 'page')
                <li><span>{{$data['title']}}</span></li>
            @elseif($post_type == 'blog')
                <li><span>{{__('Blog')}}</span></li>
            @elseif($post_type == 'term')
                @if($data['type'] == 'category')
                    <li><span>{{sprintf(__('Category: %s'), $data['title'])}}</span></li>
                @elseif($data['type'] == 'tag')
                    <li><span>{{sprintf(__('Tag: %s'), $data['title'])}}</span></li>
                @else
                    <li><span>{{$data['title']}}</span></li>
                @endif
            @else
                <li><span>{{get_translate($post['post_title'])}}</span></li>
            @endif
        </ul>
    </div>
</div>