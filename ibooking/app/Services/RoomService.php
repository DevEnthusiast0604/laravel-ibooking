<?php

namespace App\Services;

use App\Repositories\CommentRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RoomAvailabilityRepository;
use App\Repositories\RoomRepository;
use App\Repositories\TermRelationRepository;
use App\Repositories\TermRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomService extends AbstractService
{
    private static $_inst;
    protected $repository;

    public static function inst()
    {
        if (empty(self::$_inst)) {
            self::$_inst = new self();
        }
        return self::$_inst;
    }

    public function __construct()
    {
        $this->repository = RoomRepository::inst();
    }

    public function changeStatus($request)
    {
        $params = $request->post('params', '');
        if (empty($params)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $params = json_decode(base64_decode($params), true);
        $post_id = isset($params['postID']) ? $params['postID'] : '';
        $post_hashing = isset($params['postHashing']) ? $params['postHashing'] : 'none';

        if (!gmz_compare_hashing($post_id, $post_hashing)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }
        $post = $this->repository->find($post_id);
        $statusTo = isset($params['statusTo']) ? $params['statusTo'] : '';
        $res = false;
        if ($post->status != $statusTo) {
            $res = $this->repository->update($post_id, [
                'status' => $statusTo
            ]);
        }
        if ($res) {
            $room_object = $this->repository->find($post_id);
            $hotel_id = $room_object['hotel_id'];
            $all_rooms = $this->repository->where([
                'hotel_id' => $hotel_id,
                'status' => 'publish'
            ]);

            $roomAvaiRepo = RoomAvailabilityRepository::inst();
            $roomAvaiRepo->updateByWhere([
                'hotel_id' => $hotel_id,
            ], [
                'total_room' => count($all_rooms),
            ]);
            return [
                'status' => 1,
                'message' => __('Update successfully'),
                'reload' => 1
            ];
        }

        return [
            'status' => 0,
            'message' => __('Data is invalid')
        ];
    }

    public function roomRealPrice(Request $request)
    {
        $rooms = $request->post('room');
        $check_in = $request->post('check_in');
        $check_out = $request->post('check_out');
        $number_days = gmz_date_diff(strtotime($check_in), strtotime($check_out));
        $extra_services = $request->post('extra_service');
        $hotel_id = $request->post('hotel_id');
        $number_room = 0;
        $total = 0;

        if (!empty($rooms)) {
            foreach ($rooms as $k => $v) {
                if ($v['number'] > 0) {
                    $room = get_post($k, GMZ_SERVICE_ROOM);
                    $price = get_room_price($room, $check_in, $check_out);
                    $number_room += (int)$v['number'];
                    $total += $price * $v['number'];
                }
            }
        }

        if (!empty($extra_services)) {
            $hotel = get_post($hotel_id, GMZ_SERVICE_HOTEL);
            $extras = maybe_unserialize($hotel['extra_services']);
            $price_extra = 0;
            if (!empty($extras)) {
                foreach ($extra_services as $k => $v) {
                    if (isset($extras[$v])) {
                        $price_extra += ((float)$extras[$v]['price'] * $number_room * $number_days);
                    }
                }
            }
            $total += $price_extra;
        }

        return [
            'status' => true,
            'number' => $number_room,
            'price' => convert_price($total)
        ];
    }

    public function roomDetail(Request $request)
    {
        $params = $request->post('params', '');
        if (empty($params)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $params = json_decode(base64_decode($params), true);

        $post_id = isset($params['post_id']) ? $params['post_id'] : '';
        $post_hashing = isset($params['post_hashing']) ? $params['post_hashing'] : 'none';

        if (!gmz_compare_hashing($post_id, $post_hashing)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $data = $this->repository->find($post_id);

        return [
            'status' => true,
            'html' => view('Frontend::services.hotel.single.room-detail', ['data' => $data])->render()
        ];
    }

    public function searchRoom(Request $request)
    {
        $hotel_id = $request->post('hotel_id', '');
        $check_in = $request->post('checkIn', '');
        $check_out = $request->post('checkOut', '');
        $number_room = $request->post('number_room', 1);
        $adult = $request->post('adult', 1);
        $children = $request->post('children', 0);

        $default = [
            'hotel_id' => '',
            'check_in' => '',
            'check_out' => '',
            'number_room' => 1,
            'adult' => 1,
            'children' => 0
        ];

        $params = gmz_parse_args([
            'hotel_id' => $hotel_id,
            'check_in' => $check_in,
            'check_out' => $check_out,
            'number_room' => $number_room,
            'adult' => $adult,
            'children' => $children,
            'unavailable_id' => ''
        ], $default);

        $roomAvaiRepo = RoomAvailabilityRepository::inst();
        if (!empty($params['check_in']) && !empty($params['check_out'])) {
            $unavail = $roomAvaiRepo->getRoomUnavailable($params);
            $params['unavailable_id'] = $unavail;

            //Checking min day stay
            $hotel_object = get_post($hotel_id, GMZ_SERVICE_HOTEL);
            if ($hotel_object) {
                $min_stays = $hotel_object['min_day_stay'];
                if (!empty($min_stays) && is_numeric($min_stays)) {
                    $number_days = gmz_date_diff(strtotime($check_in), strtotime($check_out));
                    if ($number_days < $min_stays) {
                        return [
                            'status' => true,
                            'html' => view('Frontend::services.hotel.single.room-item', [
                                'error' => sprintf(__('Minimum day number is %s'), $min_stays)
                            ])->render()
                        ];
                    }
                }

                $min_before_booking = $hotel_object['min_day_booking'];
                if (!empty($min_before_booking) && is_numeric($min_before_booking)) {
                    $start_from = strtotime('+ ' . $min_before_booking . ' days', strtotime(date('Y-m-d')));
                    if (strtotime($check_in) < $start_from) {
                        return [
                            'status' => true,
                            'html' => view('Frontend::services.hotel.single.room-item', [
                                'error' => sprintf(__('You must book the service 2 days from the current date'), $min_before_booking)
                            ])->render()
                        ];
                    }
                }
            }
        }

        $number_rooms = [];
        if (!empty($params['check_in']) && !empty($params['check_out'])) {
            $number_rooms = $roomAvaiRepo->getNumberRoomAvailability($params);
        }

        $data = $this->repository->getRooms($params);

        return [
            'status' => true,
            'html' => view('Frontend::services.hotel.single.room-item', [
                'data' => $data,
                'postData' => $params,
                'numberRooms' => $number_rooms
            ])->render()
        ];
    }

    public function addRoomAvailability($post_data)
    {
        $roomAvaiRepo = RoomAvailabilityRepository::inst();
        $roomAvaiRepo->create([
            'post_id' => $post_data['id'],
            'hotel_id' => $post_data['hotel_id'],
            'total_room' => 0,
            'adult_number' => 0,
            'child_number' => 0,
            'check_in' => null,
            'check_out' => null,
            'number' => 0,
            'price' => 0,
            'booked' => 0,
            'status' => 'temp',
        ]);
    }

    public function restoreRoom($request)
    {
        $params = $request->post('params', '');
        if (empty($params)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $params = json_decode(base64_decode($params), true);
        $post_id = isset($params['postID']) ? $params['postID'] : '';
        $post_hashing = isset($params['postHashing']) ? $params['postHashing'] : 'none';

        if (!gmz_compare_hashing($post_id, $post_hashing)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $this->repository->restore($post_id);
        $roomAvaiRepo = RoomAvailabilityRepository::inst();
        $termRelationRepo = TermRelationRepository::inst();

        $termRelationRepo->restoreByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_ROOM
        ]);

        $room_object = $this->repository->find($post_id);
        $hotel_id = $room_object['hotel_id'];
        $all_rooms = $this->repository->where([
            'hotel_id' => $hotel_id,
            'status' => 'publish'
        ]);

        $roomAvaiRepo->updateByWhere([
            'hotel_id' => $hotel_id,
        ], [
            'total_room' => count($all_rooms),
        ]);

        return [
            'status' => 1,
            'message' => __('Restore successfully'),
        ];
    }

    public function hardDeleteRoom($request)
    {
        $params = $request->post('params', '');
        if (empty($params)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $params = json_decode(base64_decode($params), true);
        $post_id = isset($params['postID']) ? $params['postID'] : '';
        $post_hashing = isset($params['postHashing']) ? $params['postHashing'] : 'none';

        if (!gmz_compare_hashing($post_id, $post_hashing)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $roomAvaiRepo = RoomAvailabilityRepository::inst();
        $termRelationRepo = TermRelationRepository::inst();

        $termRelationRepo->hardDeleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_ROOM
        ]);

        $roomAvaiRepo->deleteByWhere([
            'post_id' => $post_id
        ]);

        $room_object = $this->repository->find($post_id, true);
        $hotel_id = $room_object['hotel_id'];
        $all_rooms = $this->repository->where([
            'hotel_id' => $hotel_id,
            'status' => 'publish'
        ]);

        $roomAvaiRepo->updateByWhere([
            'hotel_id' => $hotel_id,
        ], [
            'total_room' => count($all_rooms),
        ]);

        $this->repository->hardDelete($post_id);

        return [
            'status' => 1,
            'message' => __('Delete successfully'),
        ];
    }

    public function deletePost($request)
    {
        $params = $request->post('params', '');
        if (empty($params)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $params = json_decode(base64_decode($params), true);
        $post_id = isset($params['postID']) ? $params['postID'] : '';
        $post_hashing = isset($params['postHashing']) ? $params['postHashing'] : 'none';

        if (!gmz_compare_hashing($post_id, $post_hashing)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $roomAvaiRepo = RoomAvailabilityRepository::inst();
        $termRelationRepo = TermRelationRepository::inst();

        $termRelationRepo->deleteByWhere([
            'post_id' => $post_id,
            'post_type' => GMZ_SERVICE_ROOM
        ]);

        $room_object = $this->repository->find($post_id);
        $hotel_id = $room_object['hotel_id'];

        $this->repository->delete($post_id);

        $all_rooms = $this->repository->where([
            'hotel_id' => $hotel_id,
            'status' => 'publish'
        ]);

        $roomAvaiRepo->updateByWhere([
            'hotel_id' => $hotel_id,
        ], [
            'total_room' => count($all_rooms),
        ]);

        return [
            'status' => 1,
            'message' => __('Delete successfully'),
            'reload' => true
        ];
    }

    public function getPostsPagination($number = 10, $where = [])
    {
        if (is_partner()) {
            $where['author'] = get_current_user_id();
        }
        return $this->repository->paginate($number, $where, true);
    }

    public function deletePostTemp($hotel_id)
    {
        $roomAvaiRepo = RoomAvailabilityRepository::inst();
        $roomAvaiRepo->deleteByWhere([
            'hotel_id' => $hotel_id,
            'status' => 'temp'
        ]);
        return $this->repository->hardDeleteByWhere([
            'author' => get_current_user_id(),
            'status' => 'temp'
        ]);
    }

    public function storeNewPost($hotel_id)
    {
        $data = [
            'post_title' => 'New room ' . time(),
            'author' => get_current_user_id(),
            'hotel_id' => $hotel_id,
            'status' => 'temp'
        ];

        return $this->repository->save($data);
    }

    public function getPostById($id)
    {
        return $this->repository->find($id);
    }

    public function createSlug($data)
    {
        $text_slug = $data['post_title'];
        if (strpos($text_slug, '[:]')) {
            $text_slug_arr = explode('[:', $text_slug);
            $text_slug = '[:' . $text_slug_arr[1] . '[:';
            $start = strpos($text_slug, ']');
            $end = strpos($text_slug, '[');
            $text_slug = substr($text_slug, ($start + 1), ($end - $start + 2));
        }

        if (!empty($data['post_slug'])) {
            $isNewSlug = strpos($data['post_slug'], 'new-room-');
            if ($isNewSlug === false) {
                $text_slug = $data['post_slug'];
            }
        }

        $slug = Str::slug($text_slug);

        $allSlugs = $this->repository->getRelatedSlugs($slug, $data['post_id']);

        if (!$allSlugs->contains('post_slug', $slug)) {
            return $slug;
        }

        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('post_slug', $newSlug)) {
                return $newSlug;
            }
        }

        return $slug . '-' . time();
    }

    private function mergeData($post_data, $current_options)
    {
        if (!empty($current_options)) {
            $exclude_translate = ['checkbox', 'select', 'list_item'];

            foreach ($current_options as $item) {
                if (isset($item['translation']) && $item['translation'] && !in_array($item['type'], $exclude_translate)) {
                    $post_data[$item['id']] = set_translate($item['id']);
                } else {
                    if ($item['type'] == 'list_item') {
                        if (isset($post_data[$item['id']])) {
                            $value = $post_data[$item['id']];
                            $langs = get_languages();
                            $return = [];
                            if (count($langs) > 0) {

                                $field_need_trans = [];
                                foreach ($item['fields'] as $fkey => $fval) {
                                    if (isset($fval['translation']) && $fval['translation']) {
                                        array_push($field_need_trans, $fval['id']);
                                    }
                                }

                                if (!empty($value)) {
                                    foreach ($value as $key => $val) {
                                        if (!empty($val)) {
                                            foreach ($val as $key1 => $val1) {
                                                if (in_array($key, $field_need_trans)) {
                                                    $str = '';
                                                    foreach ($val1 as $key2 => $val2) {
                                                        $str .= '[:' . $langs[$key2] . ']' . $val2;
                                                    }
                                                    $str .= '[:]';
                                                    $return[$key][$key1][0] = $str;
                                                } else {
                                                    $return[$key][$key1] = $val1;
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            if (empty($return)) {
                                $return = $value;
                            }

                            $list_item_data = [];
                            if (!empty($return)) {
                                foreach ($return as $key => $val) {
                                    foreach ($val as $child_key => $child_val) {
                                        $list_item_data[$child_key][$key] = $child_val[0];
                                    }
                                }
                                $post_data[$item['id']] = serialize($list_item_data);
                            } else {
                                $post_data[$item['id']] = [];
                            }
                        } else {
                            $post_data[$item['id']] = [];
                        }
                    }
                }
                if (!isset($post_data[$item['id']])) {
                    $post_data[$item['id']] = '';
                }
            }
        }
        return $post_data;
    }

    private function updateTerm($post_id, $post_data)
    {
        if (isset($post_data['room_facilities'])) {
            $termRelationRepo = TermRelationRepository::inst();
            $all_facilities = get_terms('name', 'room-facilities', 'id');
            if (!empty($all_facilities)) {
                $facility_in_str = '(' . implode(',', $all_facilities) . ')';
                $termRelationRepo->deleteByWhereRaw("post_id = {$post_id} AND post_type = 'room' AND term_id IN {$facility_in_str}");
            }

            $room_facilities = $post_data['room_facilities'];

            if (!empty($room_facilities) && is_array($room_facilities)) {
                foreach ($room_facilities as $item) {
                    $data_insert = [
                        'term_id' => $item,
                        'post_id' => $post_id,
                        'post_type' => 'room'
                    ];
                    $termRelationRepo->create($data_insert);
                }
                $post_data['room_facilities'] = implode(',', $room_facilities);
            } else {
                $post_data['room_facilities'] = '';
            }
        }
        return $post_data;
    }

    public function savePost($request)
    {
        $post_id = $request->post('post_id', '');
        if (!empty($post_id)) {
            $current_options = $request->post('current_options', '');
            $current_options = json_decode(base64_decode($current_options), true);

            $post_data = $this->mergeData($request->all(), $current_options);
            $currentRoom = $this->repository->find($post_id);

            if (isset($post_data['post_content'])) {
                $post_data['post_content'] = balance_tags($post_data['post_content']);
            }

            if (isset($post_data['number_of_room'])) {
                $post_data['number_of_room'] = intval($post_data['number_of_room']);
            }

            if (isset($post_data['number_of_bed'])) {
                $post_data['number_of_bed'] = intval($post_data['number_of_bed']);
            }

            if (isset($post_data['number_of_adult'])) {
                $post_data['number_of_adult'] = intval($post_data['number_of_adult']);
            }

            if (isset($post_data['number_of_children'])) {
                $post_data['number_of_children'] = intval($post_data['number_of_children']);
            }

            if (isset($post_data['base_price'])) {
                $post_data['base_price'] = floatval($post_data['base_price']);
            }

            $post_data = $this->updateTerm($post_id, $post_data);

            if (isset($post_data['status'])) {
                if (!in_array($post_data['status'], ['publish', 'draft'])) {
                    $post_data['status'] = 'draft';
                }
            }

            $updated = $this->repository->update($post_id, $post_data);
            if (isset($post_data['number_of_room']) && isset($post_data['base_price']) && isset($post_data['number_of_adult']) && isset($post_data['number_of_children'])) {
                $hotel_id = $currentRoom['hotel_id'];
                $all_rooms = $this->repository->where([
                    'hotel_id' => $hotel_id,
                    'status' => 'publish'
                ]);

                $roomAvaiRepo = RoomAvailabilityRepository::inst();

                $roomAvaiRepo->updateByWhere([
                    'post_id' => $post_id,
                    'status' => 'temp'
                ], [
                    'status' => 'available',
                ]);


                $roomAvaiRepo->updateByWhere([
                    'post_id' => $post_id
                ], [
                    'total_room' => count($all_rooms),
                    'adult_number' => $post_data['number_of_adult'],
                    'child_number' => $post_data['number_of_children'],
                    'number' => $post_data['number_of_room']
                ]);

                $roomAvaiRepo->updateByWhere([
                    'hotel_id' => $hotel_id,
                ], [
                    'total_room' => count($all_rooms),
                ]);

                $roomAvaiRepo->updateByWhere([
                    'post_id' => $post_id,
                    'price' => $currentRoom['base_price']
                ], [
                    'price' => $post_data['base_price']
                ]);
            }

            if ($updated) {
                $response = [
                    'status' => 1,
                    'title' => __('System Alert'),
                    'message' => __('Saving data successfully')
                ];

                $finish = $request->post('finish', '');
                if ($finish) {
                    $response['redirect'] = dashboard_url('edit-room/' . $post_id);
                }

                return $response;
            }
        }

        return [
            'status' => 0,
            'title' => __('System Alert'),
            'message' => __('Saving data failed')
        ];
    }

    public function storeTermData($post_id)
    {
        $postData = $this->repository->find($post_id);
        if (!empty($postData)) {
            $tax = ['post_category', 'post_tag'];
            $termRelationRepo = TermRelationRepository::inst();
            $termRepo = TermRepository::inst();
            foreach ($tax as $item) {
                $taxName = str_replace('_', '-', $item);
                $allTax = get_terms('name', $taxName);

                $taxData = $termRelationRepo->where([
                    'post_id' => $postData->id,
                    'post_type' => 'post'
                ]);

                $res = [];
                if (!$taxData->isEmpty()) {
                    foreach ($taxData as $_item) {
                        if (isset($allTax[$_item->term_id])) {
                            array_push($res, $_item->term_id);
                        }
                    }
                }

                if ($item == 'post_category') {
                    $postData[$item] = implode(', ', $res);
                } else {
                    $postData[$item] = '';
                    if (!empty($res)) {
                        $res_in = '(' . implode(',', $res) . ')';
                        $tagData = $termRepo->whereRaw("id IN {$res_in}");
                        $tagStore = [];
                        if (!$tagData->isEmpty()) {
                            foreach ($tagData as $tag) {
                                array_push($tagStore, $tag->term_title);
                            }
                            $postData[$item] = implode(', ', $tagStore);
                        }
                    }
                }
            }
            return $postData;
        }
        return false;
    }
}