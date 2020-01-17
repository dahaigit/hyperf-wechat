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

use App\Controller\BaseController;

class WechatBaseController extends BaseController
{
    public $miniProgramApp;

    /**
     * Notes: 获取微信小程序app对象
     * User: mhl
     * @return \EasyWeChat\MiniProgram\Application
     */
    public function getMiniProgramApp()
    {
        if (is_null($this->miniProgramApp)) {
            $config = config('wechat.mini_program');
            $this->miniProgramApp = \EasyWeChat\Factory::miniProgram($config);
        }
        return $this->miniProgramApp;
    }
}
