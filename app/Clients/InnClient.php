<?php

namespace App\Clients;

use GuzzleHttp\Client as GuzzleClient;

class InnClient extends GuzzleClient
{
    private static $instance;

    private function __construct(array $config = [])
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Token 9a4f6cd37ac0af31b81068987f7a5e5fedb673da'
        ];
        $config['timeout'] = 2.0;
        $config['base_uri'] = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/';
        $config['headers'] = $headers;

        parent::__construct($config);
    }

    public static function getInstance()
    {
        if (!self::$instance instanceof InnClient) {
            self::$instance = new InnClient();
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
