@php
	$list_destination = get_option('car_list_destination');
	$search_url = url('car-search');
@endphp
@if(!empty($list_destination))
<section class="list-location py-40">
	<div class="container">
		<h2 class="section-title mb-30">{{__('List Of Destinations')}}</h2>
		<div class="carousel-s1">
			@foreach($list_destination as $item)
				@php
					$img = get_attachment_url($item['image'], [250, 150]);
					$name = get_translate($item['name']);
					$lat = floatval($item['lat']);
					$lng = floatval($item['lng']);

					$search_url = add_query_arg([
						'lat' => $lat,
						'lng' => $lng,
						'address' => $name
					], $search_url);

					$number_service = count_service_by_location(GMZ_SERVICE_CAR, $lat, $lng);

					$text = sprintf(_n(__('There are %s Car'), __('There are %s Cars'), $number_service), $number_service);
				@endphp
				<div class="location-item">
					<div class="location-item__thumbnail">
						<a href="{{esc_url($search_url)}}">
							<img class="rounded-10" src="{{esc_url($img)}}" alt="{{esc_html($name)}}">
						</a>
					</div>
					<div class="location-item__details">
						<h3 class="location-item__title"><a href="{{esc_url($search_url)}}">{{esc_html($name)}}</a></h3>
						<div class="location-item__number-car">{{$text}}</div>
						<a href="{{esc_url($search_url)}}" class="location-item__find">{{__('Find Car')}}</a>
					</div>
				</div>
			@endforeach
		</div>
	</div>
</section>
@endif