<?php

namespace App\Services;

use App\Repositories\MediaRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MediaService extends AbstractService
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
        $this->repository = MediaRepository::inst();
    }

    public function getMediaPopup($request)
    {
        $number = 40;

        $where = [];
        if (!is_admin()) {
            $where = [
                'author' => get_current_user_id()
            ];
        }
        $allMedia = $this->repository->paginate($number, $where);
        $html = '';
        if (!$allMedia->isEmpty()) {
            $totalPage = $allMedia->lastPage();
            foreach ($allMedia as $key => $attachment) {
                $html .= view('Backend::components.media.item', ['attachment' => $attachment, 'page' => $request->post('page'), 'total' => $totalPage])->render();
            }
            return [
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Loaded Media'),
                'html' => $html
            ];
        } else {
            return [
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Not found media'),
                'html' => $html
            ];
        }
    }

    public function bulkDeleteMediaItem($request)
    {
        $mediaIDs = $request->post('mediaIDs', '');
        if (empty($mediaIDs)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $mediaIDs = explode(',', $mediaIDs);

        $check_delete = 0;
        foreach ($mediaIDs as $item) {
            $deleted = false;
            if (is_admin()) {
                $deleted = $this->repository->delete($item);
            } else {
                $object = $this->repository->find($item);
                if ($object['author'] == get_current_user_id()) {
                    $deleted = $this->repository->delete($item);
                }
            }
            if ($deleted) {
                $check_delete++;
            }
        }

        if ($check_delete > 0) {
            return [
                'status' => true,
                'message' => __('Delete successfully'),
                'reload' => true
            ];
        } else {
            return [
                'status' => false,
                'message' => __('Delete failed')
            ];
        }
    }

    public function deleteMediaItem($request)
    {
        $params = $request->post('params', '');
        if (empty($params)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $params = json_decode(base64_decode($params), true);

        $media_id = isset($params['mediaID']) ? $params['mediaID'] : '';
        $media_hashing = isset($params['mediaHashing']) ? $params['mediaHashing'] : 'none';

        if (!gmz_compare_hashing($media_id, $media_hashing)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $deleted = false;
        if (is_admin()) {
            $deleted = $this->repository->delete($media_id);
        } else {
            $object = $this->repository->find($media_id);
            if ($object['author'] == get_current_user_id()) {
                $deleted = $this->repository->delete($media_id);
            }
        }

        if ($deleted) {
            return [
                'status' => true,
                'message' => __('Delete successfully'),
                'reload' => true
            ];
        } else {
            return [
                'status' => false,
                'message' => __('Delete failed')
            ];
        }
    }

    public function getMediaDetail($request)
    {
        $params = $request->post('params', '');
        if (empty($params)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $params = json_decode(base64_decode($params), true);

        $media_id = isset($params['media_id']) ? $params['media_id'] : '';
        $media_hashing = isset($params['media_hashing']) ? $params['media_hashing'] : 'none';

        if (!gmz_compare_hashing($media_id, $media_hashing)) {
            return [
                'status' => 0,
                'message' => __('Data is invalid')
            ];
        }

        $media_object = $this->repository->find($media_id);

        return [
            'status' => 1,
            'html' => view('Backend::components.modal.media-content', ['data' => $media_object])->render()
        ];
    }

    public function uploadMedia($request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 0,
                'title' => __('System Alert'),
                'message' => $validator->errors()->first()
            ];
        }

        $is_modal = $request->post('is_modal', '0');
        $file = $request->file('file');
        $name = $file->getClientOriginalName();
        if (!empty($name)) {
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            $title = pathinfo($name, PATHINFO_FILENAME);
            $size = $file->getSize();
            $name = Str::slug($title);
            $savedName = $name . '-' . time() . '.' . $ext;
            $folder = $this->getMediaFolder();
            $saved = $file->move(storage_path($folder), $savedName);
            if (!empty($saved) && is_object($saved)) {
                $data_insert = [
                    'media_title' => $title,
                    'media_name' => $name,
                    'media_url' => $this->getMediaFolder(true) . '/' . $savedName,
                    'media_path' => $saved->getPathname(),
                    'media_size' => $size,
                    'media_type' => $saved->getExtension(),
                    'media_description' => $title,
                    'author' => get_current_user_id(),
                ];
                $created = $this->repository->save($data_insert);

                if ($created) {
                    if ($is_modal == 1) {
                        $attachment = $this->repository->find($created);
                        $html = view('Backend::components.media.item', ['attachment' => $attachment])->render();
                        return [
                            'status' => 2,
                            'title' => __('System Alert'),
                            'message' => sprintf(__('The attachment %s is uploaded successfully'), $title),
                            'html' => $html
                        ];
                    } else {
                        return [
                            'status' => 2,
                            'title' => __('System Alert'),
                            'message' => sprintf(__('The attachment %s is uploaded successfully'), $title),
                        ];
                    }
                } else {
                    return [
                        'status' => 0,
                        'title' => __('System Alert'),
                        'message' => __('Have error when saving')
                    ];
                }
            } else {
                return [
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Have error when uploading')
                ];
            }
        } else {
            return [
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This file is invalid')
            ];
        }
    }

    public function getAllMedia($request)
    {
        $layout = $request->get('layout', 'grid');
        if (!in_array($layout, ['grid', 'list'])) {
            $layout = 'grid';
        }
        if ($layout == 'grid') {
            $number = 40;
        } else {
            $number = 10;
        }

        $where = [];
        if (!is_admin()) {
            $where = [
                'author' => get_current_user_id()
            ];
        }

        return $this->repository->paginate($number, $where);
    }

    public function getMediaFolder($storage = false)
    {
        $year = date('Y');
        $month = date('m');
        $date = date('d');
        if ($storage) {
            return asset('storage/' . $year . '/' . $month . '/' . $date);
        } else {
            return 'app/public/' . $year . '/' . $month . '/' . $date;
        }
    }
}