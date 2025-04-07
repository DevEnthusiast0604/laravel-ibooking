<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'gmz_notification';

    protected $fillable = [
        'user_from', 'user_to', 'title', 'message', 'type'
    ];

    public function getLatestNotificationByUser($user_id, $type = 'to')
    {
        $userdata = get_user_data($user_id);
        $last_time = $userdata['last_check_notification'];
        if(!$last_time){
            $last_time = 0;
        }
        $number = 10;
        if ($type == 'to') {
            $results = $this->query()->where('user_to', $user_id)->whereDate("created_at", '>=', $last_time)->orderByDesc('id')->paginate($number);
        } else {
            $results = $this->query()->where('user_from', $user_id)->whereDate("created_at", '>=', $last_time)->orderByDesc('id')->paginate($number);
        }

        return [
            'total' => $results->total(),
            'results' => $results->items()
        ];
    }

    public function updateLastCheckNotify($user_id, $data)
    {
        $model = new User();
        return $model->query()->where('id', $user_id)->update($data);
    }

    public function deleteNotification($noti_id)
    {
        return $this->query()->where('id', $noti_id)->delete();
    }

    public function insertNotification($data = [])
    {
        return $this->query()->insertGetId($data);
    }

    public function getTotalNotifications(){
	    $user_id = get_current_user_id();
	    return $this->query()->where('user_to', $user_id)->count();
    }
}
