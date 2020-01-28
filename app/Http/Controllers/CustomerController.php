<?php

namespace App\Http\Controllers;

use App\Customer;
// use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;

class CustomerController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \Debugbar::info($request->all());
        
        $customer = Customer::firstOrCreate(
            ['inn' => $request->inn],
            ['name' => $request->name]
        );
        return $customer;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function findByInn($id)
    {
        $client = new Client(['timeout'  => 2.0]);

        $headers = ['Content-Type' => 'application/json', 'Accept' => 'application/json', 'Authorization' => 'Token 9a4f6cd37ac0af31b81068987f7a5e5fedb673da'];
        // $body = ['query' => '5405340660'];
        $body = ['query' => $id];
        $request = new Request('POST', 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party', $headers, json_encode($body));

        $response = $client->send($request);
        return $response->getBody();
    }

    public function getStaff(Customer $customer) {
        $staff = $customer->contacts()->get();
        return $staff;
    }
}
