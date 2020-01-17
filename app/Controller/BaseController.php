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

use Hyperf\Utils\ApplicationContext;
use Psr\SimpleCache\CacheInterface;

class BaseController extends AbstractController
{

    public function response($message, array $data = [], $code = 0)
    {
        return [
            'message' => $message,
            'data' => $data,
            'code' => $code,
        ];
    }

    public function getCache()
    {
        return ApplicationContext::getContainer()->get(CacheInterface::class);
    }

}
