@php
    $data = $attachment->getAttributes();
    extract($data);
    $params = [
        'media_id' => $id,
        'media_hashing' => gmz_hashing($id)
    ];
    $img = get_attachment_url($id, [120, 120]);
    if(!isset($page)){
        $page = 1;
    }
    if(!isset($total)){
        $total = 50;
    }
@endphp
<li data-page="{{$page}}" data-total="{{$total}}">
    <div class="gmz-media-item relative" data-params="{{ base64_encode(json_encode($params)) }}">
        <div class="gmz-media-thumbnail">
            <img src="{{ $img }}" alt="{{ $media_description }}" class="img-fluid">
        </div>
        <a href="javascript:void(0)" data-media-id="{{$id}}" data-media-url="{{$img}}" class="link link-absolute gmz-open-modal" data-target="#gmz-media-item-modal" data-action="{{dashboard_url('get-media-detail')}}" data-params="{{ base64_encode(json_encode($params)) }}">&nbsp;</a>
    </div>
</li>