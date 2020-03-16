<?php

namespace App\Http\Controllers;

use App\Dealer;
use App\Project;
use App\Staff;
use App\Customer;
use App\FileProject;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;

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
        $projects = Project::paginate(10);
        foreach ($projects as $item) {
            $item->manager = $item->user()->get()[0]->name;
            $item->dealer = $item->dealer()->get()[0]->name;
            $item->products = $item->products()->get();
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
        $project->customer_staff()->associate(Staff::find($request->customer['staff_id']));

        $project->dealer()->associate(Dealer::find($request->dealer['dealer_id']));
        $project->dealer_staff()->associate(Staff::find($request->dealer['staff_id']));

        $project->save();

        $project->opponents()->attach($request->opponents);
        $project->products()->attach($request->products);
        $project->files()->attach($request->documents);

        return $project->id;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, Request $request)
    {
        $res = (object) [];
        $res->project = $project->getOriginal();
        $res->user = $project->user()->first();

        $res->dealer = $project->dealer()->first();
        $res->dealer->current_staff = $project->dealer_staff()->first();

        $res->customer = $project->customer()->first();
        $res->customer->current_staff = $project->customer_staff()->first();

        $res->opponents = $project->opponents()->get();
        $res->products = $project->products()->get();

        $res->documents = $project->files()->get();

        if ($request->ajax()) return json_encode($res);

        return view('show')->with('project', $res);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, Request $request)
    {
        if ($project->user_id != \Auth::user()->id) {
            abort(403);
        }

        $res = (object) [];
        $res->project = $project->getOriginal();
        $res->user = $project->user()->first();

        $res->dealer = $project->dealer()->first();
        $res->dealer->current_staff = $project->dealer_staff()->first();

        $res->customer = $project->customer()->first();
        $res->customer->current_staff = $project->customer_staff()->first();

        $res->opponents = $project->opponents()->get();
        $res->products = $project->products()->get();

        $res->documents = $project->files()->get();

        if ($request->ajax()) return json_encode($res);

        return view('form')->with('project', $res);
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
        \Debugbar::info($request->all());
        $project->work = $request->project['work'];
        $project->date = $request->project['date'];
        $project->tender_date = $request->project['tender_date'];
        $project->isTenderWon = $request->project['isTenderWon'];
        $project->isClosed = $request->project['isClosed'];
        if ($request->project['isClosed'] == 0) {
            $project->close_date = null;
        } else {
            $project->close_date = $request->project['close_date'];
        }
        $project->customer_staff()->associate(Staff::find($request->customer['staff_id']));
        $project->dealer_staff()->associate(Staff::find($request->dealer['staff_id']));
        $project->save();

        $project->opponents()->sync($request->opponents);
        $project->products()->sync($request->products);

        $res = $project->files()->sync($request->documents);

        if ($res['detached']) {
            foreach ($res['detached'] as $id) {
                $file = FileProject::find($id);
                if (!count($file->projects)) {
                    $file->delete();
                }
            }
        }
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
