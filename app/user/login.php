<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 6/19/2020
 * Time: 4:02 PM
 * Note: login.php
 */

namespace app\user;


use app\lib\api;
use app\lib\token;
use ext\errno;

class login extends api
{
    public $demo_user = ['demo' => 'demo'];

    /**
     * @param string $account
     * @param string $passwd
     *
     * @return array
     * @throws \Exception
     */
    public function normal(string $account, string $passwd): array
    {

        if (!isset($this->demo_user[$account])) {
            errno::set(10002);
            return [];
        }

        if ($this->demo_user[$account] !== $passwd) {
            errno::set(10001);
            return [];
        }

        return ['token' => token::new()->make(['uid' => 1], 'uid')];
    }
}