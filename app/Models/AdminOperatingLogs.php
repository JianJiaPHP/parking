<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AdminOperatingLogs extends Model
{
    protected $table = 'admin_operating_logs';
    protected $fillable = ['id', 'uid', 'router', 'method', 'content', 'desc', 'ip', 'created_at', 'updated_at'];

    /**
     * 关联管理员
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function administrator()
    {
        return $this->hasOne(Administrator::class, 'id', 'uid');
    }

    /**
     * 列表
     * @param $keyword
     * @param $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * author hy
     */
    public static function list($keyword, $limit)
    {
        $data = self::with('administrator')
            ->orderBy('id', 'desc');

        $data->when($keyword, function (Builder $query, $keyword) {
            return $query->where('router', 'like', "%$keyword%")
                ->orWhere('method', 'like', "%$keyword%")
                ->orWhere('content', 'like', "%$keyword%")
                ->orWhere('desc', 'like', "%$keyword%")
                ->orWhereHas('administrator', function ($q) use ($keyword) {
                    $q->orWhere('nickname', 'like', "%$keyword%");
                });
        });

        return $data->paginate($limit);
    }

}
