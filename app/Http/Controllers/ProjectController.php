<?php

namespace App\Http\Controllers;

use App\Dealer;
use App\Project;
use App\Staff;
use App\Customer;
use App\FileProject;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use Illuminate\Support\Facades\Storage;

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
        foreach ($projects as $item) {
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
        return redirect('/');
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
        $project = new Project($request->project);

        $project->user()->associate(\Auth::user());

        $project->customer()->associate(Customer::find($request->customer['customer_id']));
        $project->customer_staff()->associate(Staff::find($request->customer['customer_staff_id']));

        $project->dealer()->associate(Dealer::find($request->dealer['dealer_id']));
        $project->dealer_staff()->associate(Staff::find($request->dealer['dealer_staff_id']));

        $project->save();

        $project->opponents()->attach($request->opponents);
        $project->products()->attach($request->products);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Project $project)
    {
        $project->user = $project->user()->get()[0];

        $project->dealer = $project->dealer()->get()[0];
        $project->dealer_staff = $project->dealer_staff()->get()[0];

        $project->customer = $project->customer()->get()[0];
        $project->customer_staff = $project->customer_staff()->get()[0];

        $project->opponents = $project->opponents()->get();
        $project->products = $project->products()->get();

        if ($request->ajax()) return $project;

        return view('show')->with('project', $project);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Project $project)
    {
        if ($project->user_id != \Auth::user()->id) {
            abort(403);
        }

        $project->user = $project->user()->first();

        $project->dealer = $project->dealer()->first();
        $project->dealer->current_staff = $project->dealer_staff()->first();

        $project->customer = $project->customer()->first();
        $project->customer->current_staff = $project->customer_staff()->first();

        $project->opponents = $project->opponents()->get();
        $project->products = $project->products()->get();

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
    public function update(Request $request, Project $project)
    {
        // \Debugbar::info($request->all());

        $project->work = $request->project['work'];
        $project->date = $request->project['date'];
        $project->time = $request->project['time'];
        $project->customer_staff()->associate(Staff::find($request->customer['customer_staff_id']));
        $project->dealer_staff()->associate(Staff::find($request->dealer['dealer_staff_id']));
        $project->save();

        $project->opponents()->sync($request->opponents);
        $project->products()->sync($request->products);
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
}
