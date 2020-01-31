<?php

namespace App\Http\Controllers;

use App\Clients\InnClient;
use App\Clients\ZkabelClient;

class DataController extends Controller
{
    public function findByInn($id)
    {
        $client = InnClient::getInstance();
        $response = $client->post('findById/party', ['body' => json_encode(['query' => $id])]);

        if ($response->getStatusCode() != 200) {
            abort($response->getStatusCode());
        }

        $response = json_decode($response->getBody()->getContents());
        $result = [];
        foreach ($response->suggestions as $item) {

            $result[] = [
                'inn' => $item->data->inn,
                'kpp' => $item->data->kpp,
                'address' => $item->data->address->value,
                'name' => $item->value,
            ];
        }
        return $result;
    }

    public function findProductById($id)
    {
        $client = ZkabelClient::getInstance();
        $response = $client->get('catalog/findItemByArticle/?q=' . $id);


        return $response->getBody();
    }
}
