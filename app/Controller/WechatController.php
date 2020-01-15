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

namespace App\Controller;

class WechatController extends AbstractController
{
    public function getMiniProgram()
    {
        $config = config('easywechat.mini_program');
        $app = \EasyWeChat\Factory::miniProgram($config);
        return $app;
    }

    public function user()
    {
        $code = $this->request->input('code');
        $app = $this->getMiniProgram();
        $user = $app->auth->session($code);
        return [
            'user' => $user
        ];
    }
}
