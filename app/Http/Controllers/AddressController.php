<?php

namespace App\Http\Controllers;

use App\Clients\KladrClient;
use App\Http\Controllers\Controller;
use App\Project;
use Illuminate\Http\Request;

class AddressController extends Controller
{
  public function search(Request $request)
  {
    \Debugbar::info($request->all());
    $client = KladrClient::getInstance();
    $params = [
      'query' => [
        'withParent' => 1,
        'contentType' => $request['contentType'],
        'query' => $request['query']
      ]
    ];
    $response = $client->get('', $params);
    return $response->getBody();
  }

  public function searchStreet(Request $request)
  {
    \Debugbar::info($request->all());
    $client = KladrClient::getInstance();
    $params = [
      'query' => [
        'withParent' => 1,
        'contentType' => $request['contentType'],
        'cityId' => $request['cityId'],
        'query' => $request['query']
      ]
    ];
    $response = $client->get('', $params);
    return $response->getBody();
  }

  public function searchBuilding(Request $request)
  {
    \Debugbar::info($request->all());
    $client = KladrClient::getInstance();
    $params = [
      'query' => [
        'withParent' => 1,
        'contentType' => $request['contentType'],
        'streetId' => $request['streetId'],
        'query' => $request['query']
      ]
    ];
    $response = $client->get('', $params);
    return $response->getBody();
  }

  public function addresses(Request $request)
  {
    \Debugbar::info($request->all());
    if ($request->address == '') {
      return array();
    } else {
      $projects = Project::where('address', 'like', '%' . $request->address . '%')->get();
      return $projects;
    }
  }
}
