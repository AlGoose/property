<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Project;
use Illuminate\Http\Request;

class AddressController extends Controller
{
  public function getAddress()
  {
    $info = file_get_contents('https://kladr-api.ru/api.php?query=Ломоносова&contentType=street&cityId=2900000100000');
    $info = json_decode($info, true);
    print_r($info);
    print_r('<br/>');
    print_r($info['result'][1]);
    print_r('<br/>');
    print_r(date('Y-m-d'));
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
