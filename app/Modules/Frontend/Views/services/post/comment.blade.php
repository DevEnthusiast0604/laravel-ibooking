@php
    $can_review = is_user_can_review('', $post['id'], 'post');
    $comments = get_comment_list($post['id'], [
        'number' => get_comment_per_page(),
        'page' => request()->get('review_page', 1),
        'type' => 'post',
    ]);
@endphp

<div class="gmz-comment-list mt-5" id="review-section">
    <h3 class="comment-count">
        {{ _n(__('%s comment for this post'), __('%s comments for this post'), $comments->total()) }}
    </h3>
    @if(!$comments->isEmpty())
        @php
            render_list_comment($comments->items());
            echo $comments->fragment('review-section')->links();
        @endphp
    @endif
</div>

@if($can_review)
    <div class="post-comment parent-form" id="gmz-comment-section">
        <div class="comment-form-wrapper">
            <form action="{{ url('add-comment') }}" class="comment-form form-sm gmz-form-action form-add-post-comment"
                  method="post" data-reload-time="1000">
                <h3 class="comment-title">{{__('Leave a Review')}}</h3>
                <p class="notice">{{__('Your email address will not be published. Required fields are marked *')}}</p>
                @include('Frontend::components.loader')
                <input type="hidden" name="post_id" value="{{ $post['id'] }}"/>
                <input type="hidden" name="comment_id" value="0"/>
                <input type="hidden" name="comment_type" value="post"/>
                <div class="row">
                    <?php if(!is_user_login()){ ?>
                    <div class="form-group col-lg-6">
                        <input id="comment-name" type="text" name="comment_name" class="form-control gmz-validation" placeholder="{{__('Your name*')}}" data-validation="required"/>
                    </div>
                    <div class="form-group col-lg-6">
                        <input id="comment-email" type="email" name="comment_email" class="form-control gmz-validation"
                               placeholder="{{__('Your email*')}}" data-validation="required"/>
                    </div>
                    <?php } ?>
                    <div class="form-group col-lg-12">
                    <textarea id="comment-content" name="comment_content" placeholder="{{__('Comment*')}}"
                              class="form-control gmz-validation" data-validation="required" rows="4"></textarea>
                    </div>
                </div>
                <div class="gmz-message"></div>
                <button type="submit" class="btn btn-primary text-uppercase">{{__('Post Comment')}}</button>
            </form>
        </div>
    </div>
@endif