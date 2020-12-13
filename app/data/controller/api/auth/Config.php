<?php

namespace app\data\controller\api\auth;

use app\data\controller\api\Auth;
use app\data\service\PaymentService;

/**
 * 接口数据配置
 * Class Config
 * @package app\data\controller\api\auth
 */
class Config extends Auth
{
    /**
     * 获取支付通道数据
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getPayment()
    {
        $types = [];
        foreach (PaymentService::TYPES as $type => $arr) {
            if (in_array($this->type, $arr['auth'])) $types[] = $type;
        }
        $map = ['status' => 1, 'deleted' => 0];
        $query = $this->app->db->name('DataPayment')->where($map)->whereIn('type', $types);
        $result = $query->order('sort desc,id desc')->field('id,name,type,create_at')->select();
        $this->success('获取支付通道数据', $result->toArray());
    }
}