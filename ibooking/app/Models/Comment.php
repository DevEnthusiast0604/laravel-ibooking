<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $table = 'gmz_comment';
    protected $primaryKey = 'comment_id';

	protected $fillable = [
		'post_id', 'comment_title', 'comment_content', 'comment_name', 'comment_email', 'comment_author', 'comment_rate', 'post_type', 'parent', 'status'
	];

	public function getCommentByPostID($post_id, $data){
        $default = [
            'page' => 1,
            'parent' => 0,
            'type' => 'post',
            'number' => -1,
        ];
        $data = gmz_parse_args($data, $default);

        $number = $data['number'];
        if($number == -1){
            $number = null;
        }

        $post = $this->query()->selectRaw("SQL_CALC_FOUND_ROWS {$this->table}.*")
            ->where('post_id', $post_id)
            ->where('post_type', $data['type'])
            ->where('parent', $data['parent'])
            ->where('status', 'publish')
            ->orderBy('comment_id', 'DESC');

        if(is_null($number)){
            return $post->get();
        }else{
            return $post->paginate($number, '', 'review_page');
        }
    }

    public function getCommentCountByPostID($post_id, $type){
        $comment_number = $this->query()->selectRaw("count(*) as comment_number")
            ->where('post_id', $post_id)
            ->where('status', 'publish')
            ->where('post_type', $type)->count();
        return $comment_number;
    }
}
