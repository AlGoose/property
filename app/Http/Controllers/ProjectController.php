<?php

namespace App\Http\Controllers;

use App\Dealer;
use App\Http\Requests\ProjectRequest;
use App\Opponent;
use App\Product;
use App\Project;
use App\Staff;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('auth');
    }

    public function savestaff(Request $request)
    {
        $request = ['name' => 'Name1'];

        $dealer_staff = new Staff($request);

        $dealer = Dealer::find(2);
        $dealer_staff->entity()->associate($dealer);
        dd($dealer_staff, $dealer);
        $dealer_staff->save();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $projects = Project::paginate(5);
        // $dealer = Dealer::find(3);
        // \Debugbar::info($dealer->contacts()->get()[0]->name);
        // \Debugbar::info($projects->all());

        foreach ($projects as $item) {
            // \Debugbar::info($item->user()->get()[0]->name);
            $item->manager = $item->user()->get()[0]->name;
            $item->dealer = $item->dealer()->get()[0]->name;
        }


        if ($request->ajax()) return $projects;

        return view('projects')->with('projects', $projects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('welcome');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
        \Debugbar::info($request->all());
        \Debugbar::info($request->dealer);

        $project = new Project;
        $project->name = $request->project['name'];
        $project->address = $request->project['address'];
        $project->customer = $request->project['customer'];
        $project->date = $request->project['date'];
        $project->work = $request->project['work'];
        $project->user()->associate(\Auth::user());

        $dealer = Dealer::firstOrCreate(
            ['name' => $request->dealer['name']],
            ['address' =>  'ADDRESS'],
            ['inn' =>  intval($request->dealer['inn'])] //TODO: ПОЧЕМУ ЗАПИСЫВАЕТСЯ NULL?
        );
        $project->dealer()->associate($dealer)->save();
        
        foreach ($request->project['opponents'] as $name) {
            \Debugbar::info($name);
            $opponent = Opponent::firstOrCreate(
                ['name' => $name]
            );
            $project->opponents()->attach($opponent->id);
        }
        
        $product = Product::firstOrCreate(
            ['code' => 'CODE'],
            ['name' => 'NAME'],
            ['price' => intval('10')]
        );
        $project->products()->attach($product->id, ['count' => 5]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Project $project)
    {
        $project->dealer = $project->dealer()->get()[0];
        \Debugbar::info($project->opponents()->get());
        $project->opponents = $project->opponents()->get();

        if ($request->ajax()) return $project;

        return view('show')->with('project', $project);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Project $project) //TODO: Добавить view
    {
        $project->dealer = $project->dealer()->get()[0];
        $project->opponents = $project->opponents()->get();

        if ($request->ajax()) return $project;

        return view('form')->with('project', $project);
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
        $project = Project::find($id);
        $project = $request->all();
        $project->save();
        //TODO: Так можно?
        $project->name = $request->namespace;
        //... или таким образом? Или еще как?
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Project::destroy($id);
    }

    public function addresses(Request $request)
    {
        if ($request->address == '') {
            return array();
        } else {
            $projects = Project::where('address', 'like', '%' . $request->address . '%')->get();
            return $projects;
        }
    }

    public function addProduct(Request $request) {
        \Debugbar::info($request->all());

        $product = Product::firstOrCreate(
            ['code' => $request->code],
            ['name' => $request->name]
        );
        return $product;
    }

    public function addDealer(Request $request) {
        \Debugbar::info($request->all());
        
        $dealer = Dealer::firstOrCreate(
            ['inn' => $request->inn],
            ['name' => $request->name]
        );
        return $dealer;
    }

    public function addCustomer(Request $request) {
        \Debugbar::info($request->all());
        
        $customer = Customer::firstOrCreate(
            ['inn' => $request->inn],
            ['name' => $request->name]
        );
        return $customer;
    }

    public function addOpponent(Request $request) {
        \Debugbar::info($request->all());
        
        $opponent = Opponent::firstOrCreate(
            ['name' => $request->name]
        );
        return $opponent;
    }
}
