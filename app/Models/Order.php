<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use TorMorten\Eventy\Facades\Eventy;

class Order extends Model
{

    /**
     * @var string
     */
    protected $table = 'gmz_order';

    public function __construct(array $attributes = [])
    {
        $this->setFillable();
        parent::__construct($attributes);
    }

    public function setFillable()
    {
        $this->fillable = Eventy::filter('gmz_order_fillable', ['sku', 'order_token', 'description', 'post_id', 'total', 'number', 'buyer', 'owner', 'payment_type', 'checkout_data', 'token_code', 'currency', 'start_date', 'end_date', 'start_time', 'end_time', 'post_type', 'payment_status', 'transaction_id', 'status', 'first_name', 'last_name', 'email', 'phone', 'address', 'city', 'country', 'postcode', 'note', 'change_log', 'commission']);
    }

    public function appendChangeLog($id, $user, $action)
    {
        $args = [
            'user' => $user,
            'action' => $action,
            'create' => TIME()
        ];

        $data = json_encode($args) . ',';
        return DB::update('UPDATE `gmz_order` SET `change_log` = concat(ifnull(`change_log`,""),?) WHERE `id` =?', [$data, $id]);
    }

    public function getTotalOrders()
    {
        $user_id = get_current_user_id();
        return $this->query()->where('buyer', $user_id)->count();
    }
}