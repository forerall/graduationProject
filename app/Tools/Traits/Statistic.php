<?php

namespace App\Tools\Traits;

use App\Models\PageStatistic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2017/3/28
 * Time: 9:52
 */
trait Statistic
{
    /**
     * 添加统计
     * @param $page_id
     * @throws \Exception
     */
    public function addStt($page_id)
    {
        if (empty($page_id)) {
            throw new \Exception('统计page_id不能为空');
        }
        try {
            $t = time();
            $day = date('Ymd', $t);
            $hour = date('H', $t);
            $user_id = Auth::id() ? Auth::id() : 0;
            $data = [
                'page_id' => $page_id,
                'user_id' => $user_id,
                'ip_addr' => Input::ip(),
                'day' => $day,
                'hour' => $hour,
            ];

            PageStatistic::insert($data);
        } catch (\Exception $e) {
            Log::error('统计错误：');
            Log::error($e);
        }

    }

    /**
     * 获取pv数据
     * @param $page_id
     * @param $start
     * @param $end
     */
    public function getPv($page_id, $start = 0, $end = 0)
    {
        $list = PageStatistic::where('page_id', $page_id);
        if ($start > 0) {
            $list->where('day', '>', $start - 1);
        }
        if ($end > 0) {
            $list->where('day', '<', $end + 1);
        }
        return $list->groupBy('day')
            ->select(DB::raw('count(*) as n,day'))
            ->get();
    }

    /**
     * 获取uv数据
     * @param $page_id
     * @param $start
     * @param $end
     */
    public function getUv($page_id, $start, $end)
    {
        $list = PageStatistic::where('page_id', $page_id);
        if ($start > 0) {
            $list->where('day', '>', $start - 1);
        }
        if ($end > 0) {
            $list->where('day', '<', $end + 1);
        }
        return $list->groupBy('day')
            ->select(DB::raw('count(distinct ip_addr) as n,day'))
            ->get();
    }
}