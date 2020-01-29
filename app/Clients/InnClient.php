<?php

namespace App\Clients;

use GuzzleHttp\Client as GuzzleClient;

class InnClient extends GuzzleClient
{
    private static $instance;

    private function __construct(array $config = [])
    {
        // $headers = ['Content-Type' => 'application/json', 'Accept' => 'application/json', 'Authorization' => 'Token 9a4f6cd37ac0af31b81068987f7a5e5fedb673da'];
        // $request = new GRequest('POST', 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party', $headers, json_encode($body));

        $config['base_uri'] = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/';
        $config['headers']['Content-Type'] = 'application/json';
        $config['headers']['Accept'] = 'application/json';
        $config['headers']['Authorization'] = 'Token 9a4f6cd37ac0af31b81068987f7a5e5fedb673da';
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
