<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectCreateRequest;
use App\Project;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all()->pluck('name', 'id');
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tasks = Task::all()->pluck('name', 'id');
        return view('projects.create', compact('tasks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProjectCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectCreateRequest $request)
    {
        DB::beginTransaction();
        try {

            $project = Project::create($request->all());
            $project->tasks()->attach($request->get('tasks'));

            DB::commit();
        } catch (\Exception $ex) {
            dd($ex);
            DB::rollBack();
            return redirect()->back()->with('error', 'Sorry ! Error occurred during the transaction.');
        }

        return redirect()->route('projects.index')->with("success", "Project created successfully !");
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $tasks = Task::all()->pluck('name', 'id');
        return view('projects.edit', compact('project', 'tasks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        DB::beginTransaction();
        try {

            $project->update($request->all());
            $project->tasks()->sync($request->get('tasks'));

            DB::commit();
        } catch (\Exception $ex) {
            dd($ex);
            DB::rollBack();
            return redirect()->back()->with('error', 'Sorry ! Error occurred during the transaction.');
        }

        return redirect()->route('projects.index')->with("success", "Project updated successfully !");
    }


    public function tasks(Request $request, $id)
    {

        if ($request->ajax()) {

            $project = Project::findOrFail($id);
            if ($project) {

                $tasks = $project->tasks()->get()->toArray();
                return response()->json($tasks, 200);
            } else {
                return response()->json([], 200);
            }
        }

    }
}
