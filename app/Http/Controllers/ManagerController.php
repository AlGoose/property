<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManagerRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Response;


class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $managers = User::all();
        if ($request->ajax()) {
            return Response::json($managers, 200)->header('Cache-Control', 'no-store, no-cache, private');
            // return $managers;
        }

        return view('managers')->with('managers', $managers);
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
    public function store(ManagerRequest $request)
    {
        // \Debugbar::info($request->all());
        $user = User::withTrashed()->where(['email' => $request['email']])->first();

        if ($user && $user->deleted_at) {
            $user->restore();
        } else {
            $user = User::create([
                'email' => $request['email'],
                'name' => $request['name'],
                'password' => Hash::make(Str::random(12))
            ]);
        }

         $this->sendPassword($user->id);

        return $user;
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
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id == 1) {
            abort(403, "Нельзя просто так взять и удалить Админа!");
        }
        User::destroy($id);
    }

    public function sendPassword($id)
    {
        $user = User::find($id);
        $broker = Password::broker();
        $token = $broker->getToken($user);

        \Mail::to($user)->send((new \App\Mail\NewUser($user, $token)));
    }
}
