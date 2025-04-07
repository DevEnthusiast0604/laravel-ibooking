<?php

namespace App\Services;

use App\Repositories\NotificationRepository;

class NotificationService extends AbstractService
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
        $this->repository = NotificationRepository::inst();
    }

    public function getPostsPagination($number = 10, $where = [])
    {
        return $this->repository->paginate($number, $where);
    }
}