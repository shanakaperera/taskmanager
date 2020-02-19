<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TaskController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tasks = Task::all()->sortBy('priority');
        $least_priority = $tasks->count() > 0 ? $tasks->last()->priority : 0;
        return view('tasks.create', compact('least_priority'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TaskCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            Task::create($request->all());
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Sorry ! Error occurred during the transaction.');
        }

        return redirect()->route('home')->with("success", "Task created successfully !");
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TaskUpdateRequest $request
     * @param  \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        DB::beginTransaction();
        try {
            $task->update($request->all());
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Sorry ! Error occurred during the transaction.');
        }

        return redirect()->route('home')->with("success", "Task updated successfully !");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Task $task)
    {
        if ($request->ajax()) {

            DB::beginTransaction();
            try {
                $task->delete();
                DB::commit();
            } catch (\Exception $ex) {
                dd($ex);
                DB::rollBack();
                return response()->json(['message' => 'Sorry ! Error occurred during the transaction.'], 500);
            }

            return response()->json(["message" => "Task deleted successfully !"], 200);
        }
    }


    /**
     * Update the visible ordering of tasks
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request)
    {
        if ($request->ajax()) {

            $stages = json_decode($request->get('data'));

            foreach ($stages as $stage) {

                $opt = $stage->opt;// represent the adjusted order of the task
                $apt = $stage->apt;// represent the id of the task

                Task::where('id', $apt)->update(['priority' => $opt]);

            }
            return response()->json(['message' => 'Successfully updated !']);

        }
        return null;
    }
}
