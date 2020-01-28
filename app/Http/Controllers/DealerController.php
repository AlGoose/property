<?php

namespace App\Http\Controllers;

use App\Dealer;
use App\Staff;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GRequest;
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

        $dealer = Dealer::firstOrCreate($request->dealer);
        $staff=Staff::find($request->staff_id);
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
        $client = new Client(['timeout' => 2.0]);

        $headers = ['Content-Type' => 'application/json', 'Accept' => 'application/json', 'Authorization' => 'Token 9a4f6cd37ac0af31b81068987f7a5e5fedb673da'];
        // $body = ['query' => '5405340660'];
        // 7728168971 АЛЬФАБАНК
        $body = ['query' => $id];
        $request = new GRequest('POST', 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party', $headers, json_encode($body));

        $response = $client->send($request)->getBody();
        $response = json_decode($response->read(5000000));
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
        //   inn: item.data.inn,
        //   kpp: item.data.kpp,
        //   name: item.value,
        //   address: item.data.address.value

        return $result;
    }

    public function getStaff($id)
    {
        $staff = Dealer::find($id)->contacts()->get();
        return $staff;
    }
}
