<?php


namespace App\Services;


use App\Models\Config;

class ConfigService
{
    /**
     * 获取所有配置
     * @return mixed
     * author II
     */
    public function getAll(): array
    {
        return (new Config())->getAll();
    }

    /**
     * 根据key获取vlaue
     * @param string $key
     * @return string
     * author II
     */
    public function getOne(string $key): string
    {
        $data = $this->getAll();

        return isset($data[$key]) ? $data[$key] : '';
    }

    /**
     * 后台配置
     * @return mixed
     * author hy
     */
    public function list($group)
    {
        $config = Config::getByWhere(['group' =>$group]);

        $data = [];
        foreach ($config as $v) {
            $data[$v['key']] = [
                'value' => $v['value'],
                'id' => $v['id'],
            ];
        }
        return $data;
    }

    /**
     * 修改
     * @param $id
     * @param $value
     * @return mixed
     * author hy
     */
    public function update($id, $value)
    {
        return (new Config())->updateOrCreate(['value' => $value], $id);
    }
}
