<?php

namespace App\Services;

use App\Repositories\TaxonomyRepository;

class TaxonomyService extends AbstractService
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
        $this->repository = TaxonomyRepository::inst();
    }
}