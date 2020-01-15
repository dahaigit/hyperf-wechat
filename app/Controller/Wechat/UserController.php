<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Controller\Wechat;

class UserController extends WechatBaseController
{
    public function info()
    {
        $code = $this->request->input('code');
        $app = $this->getMiniProgram();
        $user = $app->auth->session($code);
        // 消息推送，或者微信支付 都需要accesstoken
//        $token = $app->access_token->getToken();
        return [
            'user' => $user,
//            'token' => $token
        ];
    }
}
