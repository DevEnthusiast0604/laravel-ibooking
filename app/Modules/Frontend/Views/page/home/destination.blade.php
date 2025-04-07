@php
	$list_destination = get_option('list_destination');
	$all_services = get_services_enabled();
	$number_services = count($all_services);
@endphp
@if(!empty($list_destination) && $number_services > 0)
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

					$text = '';
					$find_services = [];
					if($number_services > 1){
						foreach ($all_services as $service){
						    $search_url = url($service . '-search');
						    $search_url = add_query_arg([
								'lat' => $lat,
								'lng' => $lng,
								'address' => $name
							], $search_url);
						    $number_service = count_service_by_location($service, $lat, $lng);
						    if($service == GMZ_SERVICE_CAR){
								$find_services[] = [
									'url' => $search_url,
									'text' => sprintf(_n(__('%s Car'), __('%s Cars'), $number_service), $number_service)
								];
							}elseif($service == GMZ_SERVICE_APARTMENT){
						        $find_services[] = [
									'url' => $search_url,
									'text' => sprintf(_n(__('%s Apartment'), __('%s Apartments'), $number_service), $number_service)
								];
							}elseif($service == GMZ_SERVICE_SPACE){
						        $find_services[] = [
									'url' => $search_url,
									'text' => sprintf(__('%s Space'), $number_service)
								];
							}elseif($service == GMZ_SERVICE_TOUR){
						        $find_services[] = [
									'url' => $search_url,
									'text' => sprintf(_n(__('%s Tour'), __('%s Tours'), $number_service), $number_service)
								];
							}elseif($service == GMZ_SERVICE_HOTEL){
						        $find_services[] = [
									'url' => $search_url,
									'text' => sprintf(_n(__('%s Hotel'), __('%s Hotels'), $number_service), $number_service)
								];
							}elseif($service == GMZ_SERVICE_BEAUTY){
						        $find_services[] = [
									'url' => $search_url,
									'text' => sprintf(__('%s Beauty'), $number_service)
								];
							}
						}
					}else{
					    $search_url = url($all_services[0] . '-search');
					    $search_url = add_query_arg([
							'lat' => $lat,
							'lng' => $lng,
							'address' => $name
						], $search_url);
					    $number_service = count_service_by_location($all_services[0], $lat, $lng);
					    if($all_services[0] == GMZ_SERVICE_APARTMENT){
					        $text = sprintf(_n(__('There are %s Apartment'), __('There are %s Apartments'), $number_service), $number_service);
					    }

					    if($all_services[0] == GMZ_SERVICE_TOUR){
					        $text = sprintf(_n(__('There are %s Tour'), __('There are %s Tours'), $number_service), $number_service);
					    }

					    if($all_services[0] == GMZ_SERVICE_SPACE){
					        $text = sprintf(__('There are %s Space'), $number_service);
					    }

					    if($all_services[0] == GMZ_SERVICE_CAR){
					        $text = sprintf(_n(__('There are %s Car'), __('There are %s Cars'), $number_service), $number_service);
					    }

					    if($all_services[0] == GMZ_SERVICE_HOTEL){
					        $text = sprintf(_n(__('There are %s Hotel'), __('There are %s Hotels'), $number_service), $number_service);
					    }

					    if($all_services[0] == GMZ_SERVICE_BEAUTY){
					        $text = sprintf(__('There are %s Beauty'), $number_service);
					    }
					}
				@endphp
				<div class="location-item">
					<div class="location-item__thumbnail">
						<a href="@if($number_services == 1){{esc_url($search_url)}} @else javascript:void(0); @endif">
							<img class="rounded-10" src="{{esc_url($img)}}" alt="{{esc_html($name)}}">
						</a>
					</div>
					<div class="location-item__details">
						<h3 class="location-item__title"><a href="@if($number_services == 1){{esc_url($search_url)}} @else javascript:void(0); @endif">{{esc_html($name)}}</a></h3>
						@if($number_services == 1)
						<div class="location-item__number-car">{{$text}}</div>
						<a href="{{esc_url($search_url)}}" class="location-item__find">{{__(sprintf('Find %s', ucfirst($all_services[0])))}}</a>
						@endif
						@if(!empty($find_services))
							<div class="count-service">
								@foreach($find_services as $fservice)
									<a class="" href="{{$fservice['url']}}">{{$fservice['text']}}</a>
								@endforeach
							</div>
						@endif
					</div>
				</div>
			@endforeach
		</div>
	</div>
</section>
@endif