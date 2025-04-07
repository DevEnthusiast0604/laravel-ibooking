<div class="seo-variable" data-text-copied="{{__('Copied')}}" data-text-copy="{{__('Copy')}}">
    <div class="label">{{__('SEO Variables')}}</div>
    <ul>
        @if(isset($is_content_type))
            <li><b id="c-title-{{$variable_type}}" onclick="selectText('c-title-{{$variable_type}}')" data-toggle="tooltip"
                   title="{{__('Copy')}}">{title}</b>{{__('The Post Title')}}</li>
            <li><b id="c-description-{{$variable_type}}" onclick="selectText('c-description-{{$variable_type}}')" data-toggle="tooltip"
                   title="{{__('Copy')}}">{description}</b>{{__('The Post Description')}}</li>
        @endif
        <li><b id="c-site-name-{{$variable_type}}" onclick="selectText('c-site-name-{{$variable_type}}')" data-toggle="tooltip"
               title="{{__('Copy')}}">{site_name}</b>{{__('The Site Name')}}</li>
        <li><b id="c-site-description-{{$variable_type}}" onclick="selectText('c-site-description-{{$variable_type}}')" data-toggle="tooltip"
               title="{{__('Copy')}}">{site_description}</b>{{__('The Site Description')}}</li>
        <li><b id="c-separator-{{$variable_type}}" onclick="selectText('c-separator-{{$variable_type}}')" data-toggle="tooltip"
               title="{{__('Copy')}}">{separator}</b>{{__('The Separator Character')}}</li>
    </ul>
</div>