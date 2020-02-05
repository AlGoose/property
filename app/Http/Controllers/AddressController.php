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
    $client = KladrClient::getInstance();
    $response = $client->get('', $request->all());
    return $response->getBody();
  }

  public function addresses(Request $request)
  {
    $projects = Project::where('kladrId', '=', $request->kladrId)->with('products')->get();
    return $projects;
  }
}
