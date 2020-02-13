<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FileProject;
use Illuminate\Support\Facades\Storage;
use App\Project;

class FileController extends Controller
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
        $filesCollection = [];
        if ($request->hasFile('files')) {
            // \Debugbar::info('OK');
            $uploadFiles = $request->allFiles()['files'];

            /**@var \Illuminate\Http\UploadedFile $uploadFile */
            foreach ($uploadFiles as $uploadFile) {
                // $filesCollection[] = FileProject::store($uploadFile)->id;
                $filesCollection[] = FileProject::store($uploadFile);
            }

            // $res = Project::find($request->project_id)->files()->sync($filesCollection);

            // if ($res['detached']) {
            //     foreach ($res['detached'] as $id) {
            //         $file = FileProject::find($id);
            //         $success = Storage::delete($file->path);
            //         if ($success) {
            //             $file->delete();
            //         }
            //     }
            // }
        }
        return $filesCollection;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Выгрузка файла?
        // \Debugbar::info(Project::find(4)->files()->get());
        \Debugbar::info(FileProject::find(14)->projects);
        return '123';
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
        //Как правильно?
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $file = FileProject::find($id);
        // $result = Storage::delete($file->path);
        // if ($result) {
        //     $file->delete();
        // }
    }
}
