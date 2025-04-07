@php
    $enable = get_option($post['post_type'] . '_show_partner_info', 'on');
@endphp

@if($enable == 'on')
    @php
    $author = $post['author'];
    $avatar = get_user_avatar($author, [100, 100]);
    $userName = get_user_name($author);
    $userData = get_user_data($author);
    @endphp
    <div class="partner-info">
        <div class="info-head">
            <a href="{{url('author/' . $author)}}">
                <img src="{{$avatar}}" alt="avatar" />
                <p>
                    <span class="username">{{sprintf(__('Posted by %s'), $userName)}}</span>
                    <span class="address">{{$userData['address']}}</span>
                </p>
            </a>
        </div>
        <div class="info-body">
            {{nl2br($userData['description'])}}
        </div>
    </div>
@endif