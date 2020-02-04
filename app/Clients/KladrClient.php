<?php
namespace App\Clients;

use GuzzleHttp\Client;

class KladrClient extends Client
{
    private static $instance;
    private function __construct(array $config = [])
    {
        $config['timeout'] = 2.0;
        $config['base_uri'] = 'https://kladr-api.ru/api.php';

        parent::__construct($config);
    }

    public static function getInstance()
    {
        if (!self::$instance instanceof KladrClient) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __clone()
    {
    }

    private function __sleep()
    {
    }

    private function __wakeup()
    {
    }
}
