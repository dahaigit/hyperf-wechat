<?php

declare(strict_types=1);

namespace App\Controller\Wechat;

use App\Model\WechatUser;
use Carbon\Carbon;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Utils\ApplicationContext;
use Psr\SimpleCache\CacheInterface;


class AuthController extends WechatBaseController
{
    /**
     * Notes: 微信用户登陆，使用code（微信）换取登陆token（我们服务器生成）
     * User: mhl
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function login(RequestInterface $request, ResponseInterface $response)
    {
        $cache = ApplicationContext::getContainer()->get(CacheInterface::class);

        $loginAlgo = 'str';
        $code = $this->request->input('code');
        $app = $this->getMiniProgramApp();
        $userSession = $app->auth->session($code);
        $openId = $userSession['openid'];

        // 判断用户是否已经登陆，若已经登陆需要把旧数据移除，只保留最新数据
        $checkIsLogin = $cache->get('user:openid:' . $openId);
        if ($checkIsLogin) {
            $oldSessionKey = $checkIsLogin['session_key'];
            $cache->delete('user:token:' . hash("sha256", $openId . $oldSessionKey . $loginAlgo));
        }

        $sessionKey = $userSession['session_key'];
        // sha256生成64位字符串
        $token = hash("sha256", $openId . $sessionKey . $loginAlgo);
        $expressAt = Carbon::now()->addDay();

        $cacheData = [
            'open_id' => $openId,
            'session_key' => $userSession['session_key'],
            'express_at' => $expressAt->timestamp
        ];

        // 查询这个用户是否在数据库存在
        $dbUser = WechatUser::where('open_id', $openId)->select(['id'])->first();

        $cache->set('user:token:' . $token, $cacheData, $expressAt);
        $cache->set('user:openid:' . $openId, [
            'session_key' => $userSession['session_key']
        ], $expressAt);

        $data = [
            'token' => $token,
            'express_at' => $expressAt->timestamp,
            "is_db_user" => $dbUser ? 1 : 0
        ];
        return $this->response('请求成功', $data);
    }
}
