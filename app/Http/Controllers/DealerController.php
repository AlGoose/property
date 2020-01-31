<?php

namespace App\Http\Controllers;

use App\Dealer;
use App\Staff;
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
        // \Debugbar::info($request->all());
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
        \Debugbar::info($request->all());
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
}
