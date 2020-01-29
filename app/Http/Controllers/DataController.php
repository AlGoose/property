<?php

namespace App\Http\Controllers;

use App\Dealer;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function findByInn($id) {
        \Debugbar::info($id);
        $client = new Client(['timeout' => 2.0]);
        $headers = ['Content-Type' => 'application/json', 'Accept' => 'application/json', 'Authorization' => 'Token 9a4f6cd37ac0af31b81068987f7a5e5fedb673da'];
        $body = ['query' => $id];
        $request = new Psr7Request('POST', 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party', $headers, json_encode($body));

        $response = $client->send($request)->getBody();
        $response = json_decode($response->read(5000000));
        \Debugbar::info($response);
        
        $result = [];
        foreach ($response->suggestions as $item) {
            try {
                $result[] = Dealer::where(['inn' => $item->data->inn, 'kpp' => $item->data->kpp])->with('contacts')->firstOrFail();
            } catch (ModelNotFoundException $exception) {
                $result[] = [
                    'inn' => $item->data->inn,
                    'kpp' => $item->data->kpp,
                    'address' => $item->data->address->value,
                    'name' => $item->value,
                ];
            }
        }
        return $result;
    }
}
