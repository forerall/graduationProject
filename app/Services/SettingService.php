<?php
/**
 * Created by PhpStorm.
 * User: ygy
 * Date: 2017/6/5
 * Time: 17:45
 */

namespace App\Services;


use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    protected $cacheKey = 'system_setting';
    /**
     * 全部系统配置
     * @var array
     */
    public static $config = [
        [
            'group_id' => 1,
            'group_name' => '其他',
            'group_list' => [
                'aaa' => ['name' => '', 'default' => '']
            ]
        ],
        [
            'group_id' => 2,
            'group_name' => '微信',
            'group_list' => [
                'app_id' => ['name' => '', 'default' => ''],
                'app_secret' => ['name' => '', 'default' => ''],
            ]
        ]
    ];

    /**
     * 更新配置到数据库
     */
    public function saveToDb()
    {
        $settings = [];
        $created_at = Carbon::now();
        $updated_at = Carbon::now();
        foreach (self::$config as $val) {
            foreach ($val['group_list'] as $k => $v) {
                $settings[] = [
                    'group_id' => $val['group_id'],
                    'group_name' => $val['group_name'],
                    'key' => $k,
                    'name' => $v['name'],
                    'value' => $v['default'],
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ];
            }
        }
        if ($settings) {
            Setting::insert($settings);
        }
    }


    /**
     * 读取配置
     * @param bool $fromCache 是否读取缓存，默认读取
     * @return mixed
     */
    public function getSettings($fromCache = true)
    {
        if (!$fromCache) {
            return $this->getSettingFromDb();
        }
        return Cache::rememberForever($this->cacheKey, function () {
            $setting = $this->getSettingFromDb();
            return $setting->toArray();
        });
    }

    protected function getSettingFromDb()
    {
        $list = Setting::orderBy('group_id')->get();
        return $list->groupBy('group_id');

    }
}