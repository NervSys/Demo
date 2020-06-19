<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 6/18/2020
 * Time: 11:44 AM
 * Note: base.php
 */

namespace app\lib;

use ext\cache;
use ext\conf;
use ext\crypt;
use ext\errno;
use ext\factory;
use ext\lock;
use ext\mysql;
use ext\pdo;
use ext\queue;
use ext\redis;

/**
 * Class base
 *
 * @package app
 */
class base extends factory
{
    /** @var \ext\mysql $mysql */
    public $mysql;

    /** @var \Redis $redis */
    public $redis;

    /** @var \ext\lock $lock */
    public $lock;

    /** @var \ext\cache $cache */
    public $cache;

    /** @var \ext\queue $queue */
    public $queue;

    /** @var \ext\crypt $crypt */
    public $crypt;

    /** @var string $env */
    public $env = 'prod';

    /**
     * base constructor.
     *
     * @throws \RedisException
     * @throws \ReflectionException
     */
    public function __construct()
    {
        //判断环境设置
        if (is_file($env_file = realpath(ROOT . '/conf/.env'))) {
            $env = trim(file_get_contents($env_file));

            if (is_file($conf_file = realpath(ROOT . '/conf/' . $env . '.ini'))) {
                $this->env = &$env;
            }
        }

        //加载配置
        conf::load('conf', $this->env);

        //加载错误码
        errno::load('app/lib/msg', 'error');

        //初始化配置
        self::init();
    }

    /**
     * 初始化配置
     *
     * @throws \RedisException
     * @throws \ReflectionException
     */
    public function init(): void
    {
        $this->mysql = mysql::new()->use_pdo(pdo::create(conf::get('mysql'))->connect());
        $this->redis = redis::create(conf::get('redis'))->connect();

        $this->lock  = lock::new()->use_redis($this->redis);
        $this->cache = cache::new()->use_redis($this->redis);
        $this->queue = queue::new()->use_redis($this->redis);
        $this->crypt = crypt::new();
    }
}