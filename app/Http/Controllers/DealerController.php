<?php

namespace App\Http\Controllers;

use App\Dealer;
use App\Staff;
use App\Clients\InnClient;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DealerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \Debugbar::info($request->all());
        $dealer = Dealer::firstOrCreate($request->dealer);
        $staff = Staff::find($request->staff_id);
        $staff->entity()->associate($dealer)->save();
        return $dealer;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Dealer $dealer)
    {
        return $dealer;
        //
    }

    public function findDealer(Request $request)
    {
        try {
            $result = Dealer::where(['inn' => $request->inn, 'kpp' => $request->kpp])->with('contacts')->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return null;
        }
        return $result;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function findByInn($id)
    {
        // $client = new Client(['timeout' => 2.0]);

        // $headers = ['Content-Type' => 'application/json', 'Accept' => 'application/json', 'Authorization' => 'Token 9a4f6cd37ac0af31b81068987f7a5e5fedb673da'];
        // $body = ['query' => '5405340660'];
        // 7728168971 АЛЬФАБАНК
        // $body = ['query' => $id];
        // $request = new Psr7Request('POST', 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party', $headers, json_encode($body));

        // $response = $client->send($request)->getBody();
        // 7728168971
        // \Debugbar::info($id);

        $client = InnClient::getInstance();
        // \Debugbar::info($client);
        $response = $client->request('POST', '/findById/party', ['query' => $id])->getBody();
        // \Debugbar::info($response);
        $response = json_decode($response->read(5000000));
        // $response = json_encode($response->getBody()->read(5000000));
        // $response = json_decode((string)$response->getBody());
        \Debugbar::info($response);

        $result = [];
        // foreach ($response->suggestions as $item) {
        //     try {
        //         $result[] = Dealer::where(['inn' => $item->data->inn, 'kpp' => $item->data->kpp])->with('contacts')->firstOrFail();
        //     } catch (ModelNotFoundException $exception) {
        //         $result[] = [
        //             'inn' => $item->data->inn,
        //             'kpp' => $item->data->kpp,
        //             'address' => $item->data->address->value,
        //             'name' => $item->value,
        //         ];
        //     }
        // }

        return $result;
    }

    public function getStaff(Dealer $dealer)
    {
        return $dealer->contacts()->get();
    }
}
