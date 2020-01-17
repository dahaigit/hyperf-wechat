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

use App\Lib\WxDataCrypt\WXBizDataCrypt;
use App\Model\WechatUser;

class UserController extends WechatBaseController
{
    /**
     * Notes: 获取用户信息
     * User: mhl
     * @return array
     */
    public function info()
    {
        $code = $this->request->input('code');
        $app = $this->getMiniProgramApp();
        $userSession = $app->auth->session($code);
        $data = ['userSession' => $userSession];
        return $this->response('请求成功', $data);
    }

    /**
     * Notes: 获取服务端token，消息推送，或者微信支付 都需要
     * User: mhl
     */
    public function getServerToken()
    {
        // 消息推送，或者微信支付 都需要accesstoken
        $app = $this->getMiniProgramApp();
        $token = $app->access_token->getToken();

        return $this->response('请求成功', [
            'token' => $token
        ]);
    }

    /**
     * Notes: 解析用户
     * User: mhl
     */
    public function Parse()
    {
        $iv = $this->request->input('iv');
        $token = $this->request->input('token');
        $userToken = $this->getCache()->get('user:token:' . $token);
        $encryptedData = $this->request->input('encryptedData');
        $pc = new WXBizDataCrypt(config('wechat.mini_program.app_id'), $userToken['session_key']);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        if ($errCode == 0) {
            $data = json_decode($data, true);
            WechatUser::firstOrCreate([
                'open_id' => $data['openId'],
                'nickname' => $data['nickName'],
                'gender' => $data['gender'],
                'language' => $data['language'],
                'mobile' => $data['mobile'] ?? '',
                'city' => $data['city'],
                'province' => $data['province'],
                'country' => $data['country'],
                'avatarUrl' => $data['avatarUrl'],
                'unique_id' => $data['unique_id'] ?? '',
            ]);
            $this->response('解析成功');
        } else {
            $this->response('解析失败');
        }
    }
}
