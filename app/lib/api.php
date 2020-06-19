<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 6/18/2020
 * Time: 11:44 AM
 * Note: api.php
 */

namespace app\lib;

use app\common\com_stats;
use app\lib\enum\enum_lock_cmd;
use app\lib\enum\enum_web_cmd;
use core\lib\stc\factory;
use core\lib\std\pool;
use ext\core;
use ext\errno;

/**
 * Class api
 *
 * 所有对外 API 暴露类请继承这个，省的写 $tz 了
 *
 * @package app\lib
 */
class api extends base
{
    public $tz = '*';

    public $uid = 0;

    /**
     * api constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();

        //默认操作成功，具体状态码在业务中修改
        errno::set(10000, 0);

        //入参过滤
        $unit_pool = factory::build(pool::class);
        $this->escape($unit_pool->data);

        //解析token
        $this->check_token();
    }

    /**
     * 解析token
     *
     * @throws \Exception
     */
    public function check_token()
    {
        $token = core::get_data('token_app');
        if (empty($token)) {
            return;
        }

        $data = token::new()->parse($token, 'uid');
        if ($data['status'] !== 0) {
            core::stop();
        }

        $this->uid = $data['uid'];
    }

    /**
     * 过滤输入
     *
     * @param array $input_data
     */
    private function escape(array &$input_data): void
    {
        foreach ($input_data as $key => &$value) {
            if (is_array($value)) {
                $this->escape($value);
                continue;
            }

            if (is_string($value)) {
                $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
        }
    }
}