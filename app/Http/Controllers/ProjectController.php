<?php

namespace App\Http\Controllers;

use App\Dealer;
use App\Http\Requests\ProjectRequest;
use App\Opponent;
use App\Product;
use App\Project;
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $projects = Project::paginate(5);


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
        // \Debugbar::info($request->all());

        $project = new Project;
        $project->name = $request->name;
        $project->address = $request->address;
        $project->customer = $request->customer;
        $project->contacts = $request->contacts;
        $project->date = $request->date;
        $project->user()->associate(\Auth::user());

        $dealer = Dealer::firstOrCreate(
            ['name' => $request->dealer_name],
            ['phone' => $request->dealer_phone]
        );
        $project->dealer()->associate($dealer)->save();

        foreach ($request->opponents as $name) {
            $opponent = Opponent::firstOrCreate(
                ['name' => $name]
            );
            $project->opponents()->attach($opponent->id);
        }

        $product = Product::firstOrCreate(
            ['code' => 'CODE'],
            ['name' => 'NAME']
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
        if ($request->ajax()) return $project;
        
        return view('show')->with('project', $project);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project) //TODO: Добавить view
    {
        return view('edit')->with('project', $project);
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
}
