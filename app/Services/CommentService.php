<?php

namespace App\Services;

use App\Repositories\CommentRepository;
use Illuminate\Http\Request;

class CommentService extends AbstractService
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
        $this->repository = CommentRepository::inst();
    }

    public function deleteItem(Request $request)
    {
        if (is_admin()) {
            $params = json_decode(base64_decode($request->post('params', [])), true);
            $post_type = isset($params['commentType']) ? $params['commentType'] : 'post';

            $post_id = isset($params['postID']) ? $params['postID'] : '';
            $post_hashing = isset($params['postHashing']) ? $params['postHashing'] : '';

            if (!gmz_compare_hashing($post_id, $post_hashing)) {
                return [
                    'status' => 0,
                    'title' => __(__('System Alert')),
                    'message' => __('This item is invalid')
                ];
            }

            $comment_object = $this->repository->find($post_id);

            $deleted = $this->repository->hardDeleteByWhereRaw("comment_id = {$post_id} OR parent = {$post_id}");

            if ($post_type !== 'post') {
                $this->updateRating($comment_object['post_id'], $post_type);
            }

            if ($deleted) {
                return [
                    'status' => 1,
                    'title' => __(__('System Alert')),
                    'message' => __('Delete Successfully'),
                ];
            } else {
                return [
                    'status' => 0,
                    'title' => __(__('System Alert')),
                    'message' => __('Can not delete this item')
                ];
            }
        }
        return [
            'status' => 0,
            'title' => __(__('System Alert')),
            'message' => __('Can not delete this item')
        ];
    }

    public function changeStatus(Request $request)
    {
        if (is_admin()) {
            $params = json_decode(base64_decode($request->post('params', [])), true);
            $status = $request->post('approve', 'on');
            $post_id = isset($params['postID']) ? $params['postID'] : '';
            $post_hashing = isset($params['postHashing']) ? $params['postHashing'] : '';

            if (!gmz_compare_hashing($post_id, $post_hashing)) {
                return [
                    'status' => 0,
                    'title' => __(__('System Alert')),
                    'message' => __('This item is invalid')
                ];
            }

            $data = [
                'status' => $status == 'yes' ? 'publish' : 'pending'
            ];

            $updated = $this->repository->update($post_id, $data);
            $comment_object = $this->repository->find($post_id);

            if ($comment_object['post_type'] !== 'post') {
                $this->updateRating($comment_object['post_id'], $comment_object['post_type']);
            }

            if ($updated) {
                return [
                    'status' => 1,
                    'title' => __(__('System Alert')),
                    'message' => __('Updated Successfully'),
                ];
            } else {
                return [
                    'status' => 0,
                    'title' => __(__('System Alert')),
                    'message' => __('Can not update this item')
                ];
            }
        }
        return [
            'status' => 0,
            'title' => __(__('System Alert')),
            'message' => __('Can not update this item')
        ];
    }

    public function getReviewsPagination($post_type, $number)
    {
        $status = \request()->get('status', '');
        $where = [
            'post_type' => $post_type
        ];
        if (in_array($status, ['publish', 'pending'])) {
            $where['status'] = $status;
        }

        $where_in = [];
        if (is_partner()) {
            $where_in = [
                'column' => 'post_id',
                'value' => []
            ];

            $repoName = '\\App\\Repositories\\' . ucfirst($post_type) . 'Repository';
            $serviceRepo = $repoName::inst();
            $allPost = $serviceRepo->getAllPostIDs(get_current_user_id());
            if (!empty($allPost)) {
                $where_in = [
                    'column' => 'post_id',
                    'value' => $allPost
                ];
            }
        }

        $data = $this->repository->paginate($number, $where, false, $where_in);

        return $data;
    }

    public function addComment(Request $request)
    {
        $post_id = $request->get('post_id');
        $comment_name = $request->get('comment_name');
        $comment_email = $request->get('comment_email');
        $comment_content = $request->get('comment_content');
        $comment_title = $request->get('comment_title');
        $comment_rate = $request->get('review_star', 5);
        $parent_id = $request->get('comment_id', 0);
        $comment_type = $request->get('comment_type', 'post');

        if (!is_enable_review()) {
            return [
                'status' => 0,
                'message' => __('Review function was closed')
            ];
        }

        $review_services = [GMZ_SERVICE_CAR, GMZ_SERVICE_APARTMENT, GMZ_SERVICE_SPACE, GMZ_SERVICE_HOTEL, GMZ_SERVICE_TOUR, GMZ_SERVICE_BEAUTY];

        $text = __('comment');
        if (in_array($comment_type, $review_services)) {
            $text = __('review');
        }

        $status = 'publish';
        if (is_need_approve_review() && !is_admin()) {
            $status = 'pending';
        }

        if (is_user_login()) {
            $comment_email = get_user_email();
            $comment_name = get_user_name();
        }

        if ($comment_rate < 0 || $comment_rate > 5) {
            $comment_rate = 5;
        }

        if ($comment_name) {
            $data = [
                'post_id' => intval($post_id),
                'comment_name' => $comment_name,
                'comment_title' => $comment_title,
                'comment_content' => $comment_content,
                'comment_rate' => $comment_rate,
                'comment_email' => $comment_email,
                'comment_author' => get_current_user_id(),
                'post_type' => $comment_type,
                'parent' => $parent_id,
                'status' => $status
            ];

            $newComment = $this->repository->create($data);
            if ($newComment) {
                if (in_array($comment_type, $review_services)) {
                    $this->updateRating($post_id, $comment_type);
                }

                $success_text = sprintf(__('Add new %s successfully'), $text);

                if (is_need_approve_review() && !is_admin()) {
                    $success_text = sprintf(__('Add new %s successfully. Your %s needs to be moderated before publishing'), $text, $text);
                }

                return [
                    'status' => true,
                    'message' => $success_text,
                    'reload' => true
                ];
            } else {
                return [
                    'status' => false,
                    'message' => sprintf(__('Can not add this %s'), $text)
                ];
            }
        } else {
            return [
                'status' => false,
                'message' => __('Some fields is incorrect')
            ];
        }
    }

    public function updateRating($post_id, $comment_type = 'post')
    {
        $comments = get_comment_list($post_id, [
            'number' => -1,
            'type' => $comment_type,
        ]);
        if (!$comments->isEmpty()) {
            $ratings = [];
            foreach ($comments as $item) {
                array_push($ratings, $item->comment_rate);
            }
            $ratings = array_filter($ratings);
            if (count($ratings)) {
                $average = array_sum($ratings) / count($ratings);
                $average = round($average, 1);
            }
        } else {
            $average = 0;
        }
        $class = 'App\\Models\\' . ucfirst($comment_type);
        $model = new $class();
        $method = 'update' . ucfirst($comment_type);
        $model->$method($post_id, ['rating' => $average]);
    }
}