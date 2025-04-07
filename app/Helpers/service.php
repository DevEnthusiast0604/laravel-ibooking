<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 12/7/2020
 * Time: 10:26 PM
 */

use Illuminate\Support\Facades\DB;

if (!function_exists('hard_delete_wishlist')) {
    function hard_delete_wishlist($post_id, $post_type)
    {
        DB::table('gmz_wishlist')->where([
            'post_id' => $post_id,
            'post_type' => $post_type
        ])->delete();
    }
}

if (!function_exists('restore_wishlist')) {
    function restore_wishlist($post_id, $post_type)
    {
        DB::table('gmz_wishlist')->where([
            'post_id' => $post_id,
            'post_type' => $post_type
        ])->update(['deleted_at' => null]);
    }
}

if (!function_exists('delete_wishlist')) {
    function delete_wishlist($post_id, $post_type)
    {
        $user_id = get_current_user_id();
        $cache_key = 'wishlist' . $user_id . $post_type;
        \Illuminate\Support\Facades\Cache::pull($cache_key);
        DB::table('gmz_wishlist')->where([
            'post_id' => $post_id,
            'post_type' => $post_type
        ])->update(['deleted_at' => \Illuminate\Support\Carbon::now()]);
    }
}

if (!function_exists('list_wishlist')) {
    function list_wishlist($post_type)
    {
        $user_id = get_current_user_id();
        $cache_key = 'wishlist' . $user_id . $post_type;
        if (\Illuminate\Support\Facades\Cache::has($cache_key)) {
            return \Illuminate\Support\Facades\Cache::get($cache_key);
        } else {
            $data = DB::table('gmz_wishlist')->select('post_id')->where([
                'post_type' => $post_type,
                'author' => $user_id
            ])->pluck('post_id');
            if (!$data->isEmpty()) {
                $data = $data->toArray();
            } else {
                $data = [];
            }
            \Illuminate\Support\Facades\Cache::put($cache_key, $data);
            return $data;
        }
    }
}

if (!function_exists('add_wishlist_box')) {
    function add_wishlist_box($post_id, $post_type, $wrapper = true)
    {
        @ob_start();
        if (is_user_login()) {
            $wishlist = list_wishlist($post_type);
            if (!empty($wishlist) && in_array($post_id, $wishlist)) {
                $in_wishlist = true;
                $title_wishlist = __('Remove to wishlist');
            } else {
                $in_wishlist = false;
                $title_wishlist = __('Add to wishlist');
            }
            ?>
            <?php if ($wrapper) { ?>
                <div class="add-wishlist-wrapper">
            <?php } ?>
            <a href="javascript:void(0)" class="add-wishlist" data-id="<?php echo $post_id ?>"
               data-post-type="<?php echo $post_type; ?>" data-action="<?php echo url('add-wishlist'); ?>"
               data-toggle="tooltip" data-placement="top" title="<?php echo $title_wishlist; ?>">
                <?php if ($in_wishlist) { ?>
                    <i class="fas fa-heart"></i>
                <?php } else { ?>
                    <i class="fal fa-heart"></i>
                <?php } ?>
                <i class="fad fa-spinner-third spinner fa-spin fa-loading"></i>
            </a>
            <?php if ($wrapper) { ?>
                </div>
            <?php } ?>
            <?php
        } else {
            ?>
            <?php if ($wrapper) { ?>
                <div class="add-wishlist-wrapper">
            <?php } ?>
            <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup" data-effect="mfp-zoom-in"><i
                        class="fal fa-heart"></i></a>
            <?php if ($wrapper) { ?>
                </div>
            <?php } ?>
            <?php
        }
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}

if (!function_exists('get_post_type_by_object')) {
    function get_post_type_by_object($object)
    {
        $getNameTable = $object->getTable();
        $postType = substr($getNameTable, 4);
        return $postType;
    }
}

if (!function_exists('hotel_star')) {
    function hotel_star($rate)
    {
        $res = '';
        if (!empty($rate)) {
            $res .= '<div class="star-rating">';
            for ($i = 1; $i <= $rate; $i++) {
                $res .= '<i class="fa fa-star"></i>';
            }
            $res .= '</div>';
        }

        return $res;
    }
}

if (!function_exists('get_post')) {
    function get_post($post_id, $post_type = 'post')
    {
        switch ($post_type) {
            case 'post':
            default:
                $model = new \App\Models\Post();
                break;
            case 'page':
                $model = new \App\Models\Page();
                break;
            case GMZ_SERVICE_CAR:
                $model = new \App\Models\Car();
                break;
            case GMZ_SERVICE_APARTMENT:
                $model = new \App\Models\Apartment();
                break;
            case GMZ_SERVICE_TOUR:
                $model = new \App\Models\Tour();
                break;
            case GMZ_SERVICE_SPACE:
                $model = new \App\Models\Space();
                break;
            case GMZ_SERVICE_HOTEL:
                $model = new \App\Models\Hotel();
                break;
            case GMZ_SERVICE_ROOM:
                $model = new \App\Models\Room();
                break;
            case GMZ_SERVICE_BEAUTY:
                $model = new \App\Models\Beauty();
                break;
            case GMZ_SERVICE_AGENT:
                $model = new \App\Models\Agent();
                break;
        }

        return $model->getPost($post_id);
    }
}

if (!function_exists('get_posts')) {
    function get_posts($args = [])
    {
        $default = [
            'post_type' => 'post',
            'posts_per_page' => -1,
            'orderby' => 'id',
            'order' => 'DESC',
            'post_not_in' => [],
            'nearby' => [],
            'terms' => [],
            'status' => 'publish'
        ];
        $args = gmz_parse_args($args, $default);
        switch ($args['post_type']) {
            case 'post':
            default:
                $model = new \App\Models\Post();
                break;
            case 'page':
                $model = new \App\Models\Page();
                break;
            case GMZ_SERVICE_CAR:
                $model = new \App\Models\Car();
                break;
            case GMZ_SERVICE_APARTMENT:
                $model = new \App\Models\Apartment();
                break;
            case GMZ_SERVICE_TOUR:
                $model = new \App\Models\Tour();
                break;
            case GMZ_SERVICE_SPACE:
                $model = new \App\Models\Space();
                break;
            case GMZ_SERVICE_HOTEL:
                $model = new \App\Models\Hotel();
                break;
            case GMZ_SERVICE_BEAUTY:
                $model = new \App\Models\Beauty();
                break;
        }

        return $model->getPosts($args);
    }
}

if (!function_exists('get_the_permalink')) {
    function get_the_permalink($post_slug, $post_type = 'post')
    {
        switch ($post_type) {
            case 'post':
            default:
                return get_post_permalink($post_slug);
                break;
            case 'page':
                return get_page_permalink($post_slug);
                break;
            case GMZ_SERVICE_CAR:
                return get_car_permalink($post_slug);
                break;
            case GMZ_SERVICE_APARTMENT:
                return get_apartment_permalink($post_slug);
                break;
            case GMZ_SERVICE_TOUR:
                return get_tour_permalink($post_slug);
                break;
            case GMZ_SERVICE_SPACE:
                return get_space_permalink($post_slug);
                break;
            case GMZ_SERVICE_HOTEL:
                return get_hotel_permalink($post_slug);
                break;
            case GMZ_SERVICE_BEAUTY:
                return get_beauty_permalink($post_slug);
                break;
        }
    }
}

if (!function_exists('is_enable_service')) {
    function is_enable_service($service)
    {
        $option = get_option($service . '_enable', 'on');
        if ($option == 'off') {
            return false;
        }

        return true;
    }
}

if (!function_exists('convert_price_range')) {
    function convert_price_range($price_range)
    {
        $default = [
            'min' => 0,
            'max' => 1000000
        ];

        if (!empty($price_range)) {
            $price_range = explode(';', $price_range);
            $default['min'] = isset($price_range[0]) ? $price_range[0] : $default['min'];
            $default['max'] = isset($price_range[1]) ? $price_range[1] : $default['max'];
        }

        return $default;
    }
}

if (!function_exists('get_price_range')) {
    function get_price_range($post_type)
    {
        switch ($post_type) {
            case GMZ_SERVICE_CAR:
            default:
                $model = new \App\Models\Car();
                break;
            case GMZ_SERVICE_APARTMENT:
                $model = new \App\Models\Apartment();
                break;
            case GMZ_SERVICE_TOUR:
                $model = new \App\Models\Tour();
                break;
            case GMZ_SERVICE_SPACE:
                $model = new \App\Models\Space();
                break;
            case GMZ_SERVICE_HOTEL:
                $model = new \App\Models\Hotel();
                break;
        }
        $query = $model->query();
        if ($post_type == 'tour') {
            $min = $query->min('adult_price');
            $max = $query->max('adult_price');
        } else {
            $min = $query->min('base_price');
            $max = $query->max('base_price');
        }
        $from = $min;
        $to = $max;

        $post_data = request()->get('price_range', '');

        if (!empty($post_data)) {
            $post_data = explode(';', $post_data);
            if (count($post_data) == 2) {
                if ($post_data[0] >= $min && $post_data[0] <= $max) {
                    $from = $post_data[0];
                }
                if ($post_data[1] >= $min && $post_data[1] <= $max && $post_data[1] >= $from) {
                    $to = $post_data[1];
                }
            }
        }

        return [
            'min' => $min,
            'max' => $max,
            'from' => $from,
            'to' => $to
        ];
    }
}

if (!function_exists('get_search_string')) {
    function get_search_string($service, $total_result, $params)
    {
        $search_str = '';
        if ($service == GMZ_SERVICE_CAR) {
            if ($total_result > 0) {
                if (!empty($params['checkIn'])) {
                    $checkIn = date(get_date_format(), strtotime($params['checkIn']));
                    $checkOut = date(get_date_format(), strtotime($params['checkOut']));
                }
                if ($total_result == 1) {
                    if (empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = __('Found <b>1 Car</b>');
                    }
                    if (empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>1 Car</b> from <b>%s</b> to <b>%s</b>'), $checkIn, $checkOut);
                    }
                    if (!empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>1 Car</b> in <b>%s</b>'), urldecode($params['address']));
                    }

                    if (!empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>1 Car</b> in <b>%s</b> from <b>%s</b> to <b>%s</b>'), $params['address'], $checkIn, $checkOut);
                    }
                } else {
                    if (empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Cars</b>'), $total_result);
                    }
                    if (empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Cars</b> from <b>%s</b> to <b>%s</b>'), $total_result, $checkIn, $checkOut);
                    }
                    if (!empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Cars</b> in <b>%s</b>'), $total_result, $params['address']);
                    }

                    if (!empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Cars</b> in <b>%s</b> from <b>%s</b> to <b>%s</b>'), $total_result, $params['address'], $checkIn, $checkOut);
                    }
                }
            } else {
                $search_str = __('No Cars found');
            }
        } elseif ($service == GMZ_SERVICE_APARTMENT) {
            if ($total_result > 0) {
                if (!empty($params['checkIn'])) {
                    $checkIn = date(get_date_format(), strtotime($params['checkIn']));
                    $checkOut = date(get_date_format(), strtotime($params['checkOut']));
                }
                if ($total_result == 1) {
                    if (empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = __('Found <b>1 Apartment</b>');
                    }
                    if (empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>1 Apartment</b> from <b>%s</b> to <b>%s</b>'), $checkIn, $checkOut);
                    }
                    if (!empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>1 Apartment</b> in <b>%s</b>'), urldecode($params['address']));
                    }

                    if (!empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>1 Apartment</b> in <b>%s</b> from <b>%s</b> to <b>%s</b>'), $params['address'], $checkIn, $checkOut);
                    }
                } else {
                    if (empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Apartments</b>'), $total_result);
                    }
                    if (empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Apartments</b> from <b>%s</b> to <b>%s</b>'), $total_result, $checkIn, $checkOut);
                    }
                    if (!empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Apartments</b> in <b>%s</b>'), $total_result, $params['address']);
                    }

                    if (!empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Apartments</b> in <b>%s</b> from <b>%s</b> to <b>%s</b>'), $total_result, $params['address'], $checkIn, $checkOut);
                    }
                }
            } else {
                $search_str = __('No Apartments found');
            }
        } elseif ($service == GMZ_SERVICE_TOUR) {
            if ($total_result > 0) {
                if (!empty($params['checkIn'])) {
                    $checkIn = date(get_date_format(), strtotime($params['checkIn']));
                    $checkOut = date(get_date_format(), strtotime($params['checkOut']));
                }
                if ($total_result == 1) {
                    if (empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = __('Found <b>1 Tour</b>');
                    }
                    if (empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>1 Tour</b> from <b>%s</b> to <b>%s</b>'), $checkIn, $checkOut);
                    }
                    if (!empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>1 Tour</b> in <b>%s</b>'), urldecode($params['address']));
                    }

                    if (!empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>1 Tour</b> in <b>%s</b> from <b>%s</b> to <b>%s</b>'), $params['address'], $checkIn, $checkOut);
                    }
                } else {
                    if (empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Tours</b>'), $total_result);
                    }
                    if (empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Tours</b> from <b>%s</b> to <b>%s</b>'), $total_result, $checkIn, $checkOut);
                    }
                    if (!empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Tours</b> in <b>%s</b>'), $total_result, $params['address']);
                    }

                    if (!empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Tours</b> in <b>%s</b> from <b>%s</b> to <b>%s</b>'), $total_result, $params['address'], $checkIn, $checkOut);
                    }
                }
            } else {
                $search_str = __('No Tours found');
            }
        } elseif ($service == GMZ_SERVICE_SPACE) {
            if ($total_result > 0) {
                if (!empty($params['checkIn'])) {
                    $checkIn = date(get_date_format(), strtotime($params['checkIn']));
                    $checkOut = date(get_date_format(), strtotime($params['checkOut']));
                }
                if ($total_result == 1) {
                    if (empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = __('Found <b>1 Space</b>');
                    }
                    if (empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>1 Space</b> from <b>%s</b> to <b>%s</b>'), $checkIn, $checkOut);
                    }
                    if (!empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>1 Space</b> in <b>%s</b>'), urldecode($params['address']));
                    }

                    if (!empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>1 Space</b> in <b>%s</b> from <b>%s</b> to <b>%s</b>'), $params['address'], $checkIn, $checkOut);
                    }
                } else {
                    if (empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Space</b>'), $total_result);
                    }
                    if (empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Space</b> from <b>%s</b> to <b>%s</b>'), $total_result, $checkIn, $checkOut);
                    }
                    if (!empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Space</b> in <b>%s</b>'), $total_result, $params['address']);
                    }

                    if (!empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Space</b> in <b>%s</b> from <b>%s</b> to <b>%s</b>'), $total_result, $params['address'], $checkIn, $checkOut);
                    }
                }
            } else {
                $search_str = __('No Space found');
            }
        } elseif ($service == GMZ_SERVICE_HOTEL) {
            if ($total_result > 0) {
                if (!empty($params['checkIn'])) {
                    $checkIn = date(get_date_format(), strtotime($params['checkIn']));
                    $checkOut = date(get_date_format(), strtotime($params['checkOut']));
                }
                if ($total_result == 1) {
                    if (empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = __('Found <b>1 Hotel</b>');
                    }
                    if (empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>1 Hotel</b> from <b>%s</b> to <b>%s</b>'), $checkIn, $checkOut);
                    }
                    if (!empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>1 Hotel</b> in <b>%s</b>'), urldecode($params['address']));
                    }

                    if (!empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>1 Hotel</b> in <b>%s</b> from <b>%s</b> to <b>%s</b>'), $params['address'], $checkIn, $checkOut);
                    }
                } else {
                    if (empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Hotels</b>'), $total_result);
                    }
                    if (empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Hotels</b> from <b>%s</b> to <b>%s</b>'), $total_result, $checkIn, $checkOut);
                    }
                    if (!empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Hotels</b> in <b>%s</b>'), $total_result, $params['address']);
                    }

                    if (!empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Hotels</b> in <b>%s</b> from <b>%s</b> to <b>%s</b>'), $total_result, $params['address'], $checkIn, $checkOut);
                    }
                }
            } else {
                $search_str = __('No Hotels found');
            }
        } elseif ($service == GMZ_SERVICE_BEAUTY) {
            if ($total_result > 0) {
                if (!empty($params['checkIn'])) {
                    $checkIn = date(get_date_format(), $params['checkIn']);
                }
                if ($total_result == 1) {
                    if (empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = __('Found <b>1 Beauty</b>');
                    }
                    if (empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>1 Beauty</b> on <b>%s</b>'), $checkIn);
                    }
                    if (!empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>1 Beauty</b> in <b>%s</b>'), urldecode($params['address']));
                    }

                    if (!empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>1 Beauty</b> in <b>%s</b> on <b>%s</b>'), $params['address'], $checkIn);
                    }
                } else {
                    if (empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Beauty</b>'), $total_result);
                    }
                    if (empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Beauty</b> on <b>%s</b>'), $total_result, $checkIn);
                    }
                    if (!empty($params['address']) && empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Beauty</b> in <b>%s</b>'), $total_result, $params['address']);
                    }

                    if (!empty($params['address']) && !empty($params['checkIn'])) {
                        $search_str = sprintf(__('Found <b>%s Cars</b> in <b>%s</b> on <b>%s</b>'), $total_result, $params['address'], $checkIn);
                    }
                }
            } else {
                $search_str = __('No Beauty found');
            }
        }

        return view('Frontend::components.search-bar', [
            'search_str' => $search_str,
            'params' => $params,
            'sort' => ''
        ])->render();
    }
}

//Post
if (!function_exists('get_post_permalink')) {
    function get_post_permalink($post_slug)
    {
        return url("post/{$post_slug}");
    }
}

//Car
if (!function_exists('get_car_permalink')) {
    function get_car_permalink($post_slug)
    {
        return url("car/{$post_slug}");
    }
}

//Apartment
if (!function_exists('get_apartment_permalink')) {
    function get_apartment_permalink($post_slug)
    {
        return url("apartment/{$post_slug}");
    }
}

//Tour
if (!function_exists('get_tour_permalink')) {
    function get_tour_permalink($post_slug)
    {
        return url("tour/{$post_slug}");
    }
}

//Space
if (!function_exists('get_space_permalink')) {
    function get_space_permalink($post_slug)
    {
        return url("space/{$post_slug}");
    }
}

//page
if (!function_exists('get_page_permalink')) {
    function get_page_permalink($post_slug)
    {
        return url("page/{$post_slug}");
    }
}

//Hotel
if (!function_exists('get_hotel_permalink')) {
    function get_hotel_permalink($post_slug)
    {
        return url("hotel/{$post_slug}");
    }
}

//Beauty services
if (!function_exists('get_beauty_permalink')) {
    function get_beauty_permalink($post_slug)
    {
        return url("beauty-service/{$post_slug}");
    }
}

if (!function_exists('count_service_by_location')) {
    function count_service_by_location($post_type, $lat, $lng, $distance = 50)
    {
        switch ($post_type) {
            case GMZ_SERVICE_CAR:
            default:
                $model = new \App\Models\Car();
                break;
            case GMZ_SERVICE_APARTMENT:
                $model = new \App\Models\Apartment();
                break;
            case GMZ_SERVICE_TOUR:
                $model = new \App\Models\Tour();
                break;
            case GMZ_SERVICE_SPACE:
                $model = new \App\Models\Space();
                break;
            case GMZ_SERVICE_HOTEL:
                $model = new \App\Models\Hotel();
                break;
            case GMZ_SERVICE_BEAUTY:
                $model = new \App\Models\Beauty();
                break;
        }
        $query = $model->query();
        $query->selectRaw("*");
        $query->selectRaw("( 6371 * acos( cos( radians({$lat}) ) * cos( radians( location_lat ) ) * cos( radians( location_lng ) - radians({$lng}) ) + sin( radians({$lat}) ) * sin( radians( location_lat ) ) ) ) AS distance");
        $query->orHavingRaw("distance <= " . $distance);

        return $query->get()->count();
    }
}

if (!function_exists('the_breadcrumb')) {
    function the_breadcrumb($post, $post_type, $data = [])
    {
        echo view('Frontend::components.breadcrumb', ['post' => $post, 'post_type' => $post_type, 'data' => $data]);
    }
}

if (!function_exists('init_postdata')) {
    function init_postdata()
    {
        global $post;
    }
}

if (!function_exists('reset_postdata')) {
    function reset_postdata()
    {
        global $post;
        $post = null;
    }
}

if (!function_exists('balance_tags')) {
    function balance_tags($text, $force = false)
    {
        $text = str_replace('<script>', '&lt;script&gt;', $text);
        $text = str_replace('</script>', '&lt;/script&gt;', $text);

        return force_balance_tags($text);
    }
}

if (!function_exists('force_balance_tags')) {
    function force_balance_tags($text)
    {
        $tagstack = array();
        $stacksize = 0;
        $tagqueue = '';
        $newtext = '';

        $single_tags = array(
            'area',
            'base',
            'basefont',
            'br',
            'col',
            'command',
            'embed',
            'frame',
            'hr',
            'img',
            'input',
            'isindex',
            'link',
            'meta',
            'param',
            'source'
        );

        $nestable_tags = array('blockquote', 'div', 'object', 'q', 'span');

        $text = str_replace('< !--', '<    !--', $text);
        $text = preg_replace('#<([0-9]{1})#', '&lt;$1', $text);

        while (preg_match('/<(\/?[\w:]*)\s*([^>]*)>/', $text, $regex)) {
            $newtext .= $tagqueue;

            $i = strpos($text, $regex[0]);
            $l = strlen($regex[0]);

            $tagqueue = '';
            if (isset($regex[1][0]) && '/' == $regex[1][0]) {
                $tag = strtolower(substr($regex[1], 1));
                if ($stacksize <= 0) {
                    $tag = '';
                } elseif ($tagstack[$stacksize - 1] == $tag) {
                    $tag = '</' . $tag . '>';
                    array_pop($tagstack);
                    $stacksize--;
                } else {
                    for ($j = $stacksize - 1; $j >= 0; $j--) {
                        if ($tagstack[$j] == $tag) {
                            for ($k = $stacksize - 1; $k >= $j; $k--) {
                                $tagqueue .= '</' . array_pop($tagstack) . '>';
                                $stacksize--;
                            }
                            break;
                        }
                    }
                    $tag = '';
                }
            } else {
                $tag = strtolower($regex[1]);
                if ('' == $tag) {
                } elseif (substr($regex[2], -1) == '/') {
                    if (!in_array($tag, $single_tags)) {
                        $regex[2] = trim(substr($regex[2], 0, -1)) . "></$tag";
                    }
                } elseif (in_array($tag, $single_tags)) {
                    $regex[2] .= '/';
                } else {
                    if ($stacksize > 0 && !in_array($tag, $nestable_tags) && $tagstack[$stacksize - 1] == $tag) {
                        $tagqueue = '</' . array_pop($tagstack) . '>';
                        $stacksize--;
                    }
                    $stacksize = array_push($tagstack, $tag);
                }

                $attributes = $regex[2];
                if (!empty($attributes) && $attributes[0] != '>') {
                    $attributes = ' ' . $attributes;
                }

                $tag = '<' . $tag . $attributes . '>';
                if (!empty($tagqueue)) {
                    $tagqueue .= $tag;
                    $tag = '';
                }
            }
            $newtext .= substr($text, 0, $i) . $tag;
            $text = substr($text, $i + $l);
        }
        $newtext .= $tagqueue;
        $newtext .= $text;
        while ($x = array_pop($tagstack)) {
            $newtext .= '</' . $x . '>';
        }
        $newtext = str_replace('< !--', '<!--', $newtext);
        $newtext = str_replace('<    !--', '< !--', $newtext);

        return $newtext;
    }
}

if (!function_exists('get_services_enabled')) {
    function get_services_enabled($for_option = false)
    {
        $services = [
            GMZ_SERVICE_APARTMENT,
            GMZ_SERVICE_SPACE,
            GMZ_SERVICE_CAR,
            GMZ_SERVICE_HOTEL,
            GMZ_SERVICE_BEAUTY,
            GMZ_SERVICE_TOUR
        ];
        $res = [];
        foreach ($services as $service) {
            if (is_enable_service($service)) {
                if ($for_option) {
                    $res[$service] = ucfirst($service);
                } else {
                    $res[] = $service;
                }
            }
        }

        return $res;
    }
}

if (!function_exists('get_booking_type')) {
    function get_booking_type($type)
    {
        if ($type == 'per_hour') {
            return __('per hour');
        }

        return __('per day');
    }
}

if (!function_exists('get_filter_status')) {
    function get_filter_status($post_type = 'post')
    {
        $post_status = admin_config($post_type . '_status');
        $status = request()->get('status', '');
        if (!in_array($status, array_keys($post_status))) {
            $status = '';
        }
        $ext = $service_name = '';
        if ($post_type == GMZ_SERVICE_ROOM) {
            $hotel_id = request()->get('hotel_id');
            $ext = 'hotel_id=' . $hotel_id;
        } else if ($post_type == GMZ_SERVICE_AGENT) {
            $service_name = request()->route()->service;
            $service_name .= '/';
        }
        if (!in_array($post_type, [GMZ_SERVICE_BEAUTY], true)) {
            $post_type .= 's';
        }
        ?>
        <div class="filter-action pl-2 mt-3">
            <a href="<?php echo dashboard_url($service_name . 'all-' . $post_type . '?' . $ext) ?>"
               class="<?php if ($status == '') {
                   echo 'text-primary font-weight-bold';
               } ?> mr-2">
                <?php echo __('All') ?>
            </a>
            <?php
            if (!empty($post_status)) {
                foreach ($post_status as $kpst => $vpst) {
                    $class = 'mr-2 ';
                    if ($kpst == 'trash') {
                        $class .= ' text-danger';
                    }
                    if ($status == $kpst) {
                        $class .= ' text-primary font-weight-bold';
                    }
                    ?>
                    <a href="<?php echo dashboard_url($service_name . 'all-' . $post_type . '?status=' . $kpst . '&' . $ext) ?>"
                       class="<?php echo $class; ?>">
                        <?php echo $vpst ?>
                    </a>
                    <?php
                }
            }
            ?>
        </div>
        <?php
    }
}

if (!function_exists('get_gear_shift')) {
    function get_gear_shift($key)
    {
        $data = [
            'automatic' => __('Auto'),
            'manual' => __('Manual')
        ];
        if (isset($data[$key])) {
            return $data[$key];
        }

        return $data['automatic'];
    }
}

if (!function_exists('get_all_agent_by_author')) {
    function get_all_agent_by_author($post_type, $author)
    {
        $model = new \App\Models\Agent();
        $result = $model->newQuery()->where(['post_type' => $post_type, 'author' => $author])->get();
        $listDataAgent = [];
        if (!$result->isEmpty()) {
            foreach ($result as $value) {
                $listDataAgent[get_translate($value['post_title'])] = $value['id'];
            }
        }
        return $listDataAgent;
    }
}

if (!function_exists('get_day_of_week')) {
    function get_day_of_week()
    {
        return [
            "Sunday" => 0,
            "Monday" => 1,
            "Tuesday" => 2,
            "Wednesday" => 3,
            "Thursday" => 4,
            "Friday" => 5,
            "Saturday" => 6
        ];
    }
}

if (!function_exists('get_agents_by_ids')) {
    function get_agents_by_ids(array $ids)
    {
        $model = new \App\Models\Agent();

        return $model->newQuery()->whereIn('id', $ids)->get();
    }
}
if (!function_exists('get_agents_by_id')) {
    function get_agents_by_id(int $id)
    {
        $model = new \App\Models\Agent();
        return $model->newQuery()->where('id', $id)->first();
    }
}

if (!function_exists('get_service_settings')) {
    function get_service_settings($service = GMZ_SERVICE_ROOM)
    {
        return Eventy::filter('gmz_' . $service . '_fields', admin_config('settings', $service));
    }
}

if(!function_exists('list_service_status')) {
    function list_service_status($status = '', $post_type = '') {
        switch ($status){
            case 'publish':
                $statusArr = [
                    'draft'  => __( 'Draft' ),
                    'pending'   => __( 'Pending' )
                ];
                break;
            case 'draft':
                $statusArr = [
                    'publish'  => __( 'Publish' ),
                    'pending'   => __( 'Pending' )
                ];
                break;
            case 'pending':
                $statusArr = [
                    'publish'  => __( 'Publish' ),
                    'draft'   => __( 'Draft' )
                ];
                break;
            default:
                $statusArr = [
                    'publish'  => __( 'Publish' ),
                    'draft'   => __( 'Draft' ),
                    'pending'   => __( 'Pending' ),
                ];
                break;
        }
        if($post_type == 'post'){
            if(isset($statusArr['pending'])){
                unset($statusArr['pending']);
            }
        }
        return $statusArr;
    }
}

if(!function_exists('get_meta')){
    function get_meta($postID, $metaKey, $postType = 'page'){
        $meta = DB::table('gmz_meta')
            ->where('post_id', $postID)
            ->where('post_type', $postType)
            ->where('meta_key', $metaKey)
            ->pluck('meta_value')->first();
        return $meta;
    }
}

