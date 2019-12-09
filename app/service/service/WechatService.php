<?php

// +----------------------------------------------------------------------
// | ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2019 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://demo.thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 代码仓库：https://gitee.com/zoujingli/ThinkAdmin
// | github 代码仓库：https://github.com/zoujingli/ThinkAdmin
// +----------------------------------------------------------------------

namespace app\service\service;

use think\admin\Service;

/**
 * Class WechatService
 * @package app\service\serivce
 *
 * @method \WeChat\Card WeChatCard($appid) static 微信卡券管理
 * @method \WeChat\Custom WeChatCustom($appid) static 微信客服消息
 * @method \WeChat\Limit WeChatLimit($appid) static 接口调用频次限制
 * @method \WeChat\Media WeChatMedia($appid) static 微信素材管理
 * @method \WeChat\Menu WeChatMenu($appid) static 微信菜单管理
 * @method \WeChat\Oauth WeChatOauth($appid) static 微信网页授权
 * @method \WeChat\Pay WeChatPay($appid) static 微信支付商户
 * @method \WeChat\Product WeChatProduct($appid) static 微信商店管理
 * @method \WeChat\Qrcode WeChatQrcode($appid) static 微信二维码管理
 * @method \WeChat\Receive WeChatReceive($appid) static 微信推送管理
 * @method \WeChat\Scan WeChatScan($appid) static 微信扫一扫接入管理
 * @method \WeChat\Script WeChatScript($appid) static 微信前端支持
 * @method \WeChat\Shake WeChatShake($appid) static 微信揺一揺周边
 * @method \WeChat\Tags WeChatTags($appid) static 微信用户标签管理
 * @method \WeChat\Template WeChatTemplate($appid) static 微信模板消息
 * @method \WeChat\User WeChatUser($appid) static 微信粉丝管理
 * @method \WeChat\Wifi WeChatWifi($appid) static 微信门店WIFI管理
 *
 * ----- WeMini -----
 * @method \WeMini\Account WeMiniAccount($appid) static 小程序账号管理
 * @method \WeMini\Basic WeMiniBasic($appid) static 小程序基础信息设置
 * @method \WeMini\Code WeMiniCode($appid) static 小程序代码管理
 * @method \WeMini\Domain WeMiniDomain($appid) static 小程序域名管理
 * @method \WeMini\Tester WeMinitester($appid) static 小程序成员管理
 * @method \WeMini\User WeMiniUser($appid) static 小程序帐号管理
 * --------------------
 * @method \WeMini\Crypt WeMiniCrypt($options = []) static 小程序数据加密处理
 * @method \WeMini\Delivery WeMiniDelivery($options = []) static 小程序即时配送
 * @method \WeMini\Image WeMiniImage($options = []) static 小程序图像处理
 * @method \WeMini\Logistics WeMiniLogistics($options = []) static 小程序物流助手
 * @method \WeMini\Message WeMiniMessage($options = []) static 小程序动态消息
 * @method \WeMini\Ocr WeMiniOcr($options = []) static 小程序ORC服务
 * @method \WeMini\Plugs WeMiniPlugs($options = []) static 小程序插件管理
 * @method \WeMini\Poi WeMiniPoi($options = []) static 小程序地址管理
 * @method \WeMini\Qrcode WeMiniQrcode($options = []) static 小程序二维码管理
 * @method \WeMini\Security WeMiniSecurity($options = []) static 小程序内容安全
 * @method \WeMini\Soter WeMiniSoter($options = []) static 小程序生物认证
 * @method \WeMini\Template WeMiniTemplate($options = []) static 小程序模板消息支持
 * @method \WeMini\Total WeMiniTotal($options = []) static 小程序数据接口
 *
 * ----- WePay -----
 * @method \WePay\Bill WePayBill($appid) static 微信商户账单及评论
 * @method \WePay\Order WePayOrder($appid) static 微信商户订单
 * @method \WePay\Refund WePayRefund($appid) static 微信商户退款
 * @method \WePay\Coupon WePayCoupon($appid) static 微信商户代金券
 * @method \WePay\Redpack WePayRedpack($appid) static 微信红包支持
 * @method \WePay\Transfers WePayTransfers($appid) static 微信商户打款到零钱
 * @method \WePay\TransfersBank WePayTransfersBank($appid) static 微信商户打款到银行卡
 *
 * ----- WeOpen -----
 * @method \WeOpen\Login WeOpenLogin() static 第三方微信登录
 * @method \WeOpen\Service WeOpenService() static 第三方服务
 *
 * ----- ThinkService -----
 * @method ConfigService ThinkAdminConfig($appid) static 平台服务配置
 */
class WechatService extends Service
{

    /**
     * 静态初始化对象
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function __callStatic($name, $arguments)
    {
        list($type, $class) = ['-', '-'];
        foreach (['WeChat', 'WeMini', 'WeOpen', 'WePay', 'ThinkAdmin'] as $type) {
            if (strpos($name, $type) === 0) {
                list(, $class) = explode($type, $name);
                break;
            }
        }
        if ("{$type}{$class}" !== $name) {
            throw new \think\Exception("class {$name} not defined.");
        }
        if (in_array($type, ['WeChat', 'WePay', 'WeMini', 'ThinkAdmin'])) {
            if (empty($arguments[0])) {
                throw new \think\Exception("Appid parameter must be passed in during initialization");
            }
        }
        $classname = "\\{$type}\\{$class}";
        if (in_array($type, ['WeChat', 'WeMini', 'WePay'])) {
            return new $classname(self::instance()->getWechatConfig($arguments[0]));
        } elseif ($type === 'ThinkAdmin' && $class === 'Config') {
            return ConfigService::instance()->initialize($arguments[0]);
        } elseif ($type === 'WeOpen') {
            return new $classname(self::instance()->getServiceConfig());
        } else {
            throw new \think\Exception("class {$classname} not defined.");
        }
    }

    /**
     * 获取公众号配置参数
     * @param string $authorizerAppid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getWechatConfig($authorizerAppid)
    {
        $conifg = [
            'appid'          => $authorizerAppid,
            'token'          => sysconf('service.component_token'),
            'appsecret'      => sysconf('service.component_appsecret'),
            'encodingaeskey' => sysconf('service.component_encodingaeskey'),
            'cache_path'     => $this->getCachePath(),
        ];
        $conifg['GetAccessTokenCallback'] = function ($authorizerAppid) {
            $map = ['authorizer_appid' => $authorizerAppid];
            $refreshToken = $this->app->db->name('WechatServiceConfig')->where($map)->value('authorizer_refresh_token');
            if (empty($refreshToken)) throw new \think\Exception('The WeChat information is not configured.', '404');
            // 刷新公众号原授权 AccessToken
            $result = WechatService::WeOpenService()->refreshAccessToken($authorizerAppid, $refreshToken);
            if (empty($result['authorizer_access_token']) || empty($result['authorizer_refresh_token'])) {
                throw new \think\Exception($result['errmsg']);
            }
            // 更新公众号授权信息
            $this->app->db->name('WechatServiceConfig')->where($map)->update([
                'authorizer_access_token'  => $result['authorizer_access_token'],
                'authorizer_refresh_token' => $result['authorizer_refresh_token'],
            ]);
            return $result['authorizer_access_token'];
        };
        return $conifg;
    }

    /**
     * 获取服务平台配置参数
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getServiceConfig()
    {
        return [
            'cache_path'               => $this->getCachePath(),
            'component_appid'          => sysconf('service.component_appid'),
            'component_token'          => sysconf('service.component_token'),
            'component_appsecret'      => sysconf('service.component_appsecret'),
            'component_encodingaeskey' => sysconf('service.component_encodingaeskey'),
        ];
    }

    /**
     * 获取缓存目录
     * @return string
     */
    private function getCachePath()
    {
        return $this->app->getRuntimePath() . 'wechat';
    }
}