<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AdminLoginLog extends Model
{
    protected $table = "admin_login_logs";
    protected $fillable = ['id', 'uid', 'ip', 'country', 'city', 'created_at', 'updated_at'];

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
     * author gzy
     */
    public static function list($keyword, $limit)
    {
        $data = self::with('administrator')
            ->orderBy('id', 'desc');

        $data->when($keyword, function ($query, $keyword) {
            return $query->where('ip', 'like', "%$keyword%")
                ->orWhere('country', 'like', "%$keyword%")
                ->orWhere('city', 'like', "%$keyword%")
                ->orWhere('ip', 'like', "%$keyword%")
                ->orWhereHas('administrator', function ($q) use ($keyword) {
                    $q->orWhere('nickname', 'like', "%$keyword%");
                });
        });

        return $data->paginate($limit);
    }
}
