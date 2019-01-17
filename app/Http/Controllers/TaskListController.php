<?php

namespace App\Http\Controllers;

use App\TaskList;
use http\Env\Response;
use Illuminate\Http\Request;
use function MongoDB\BSON\toJSON;

class TaskListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tasklist');
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
        $this->validate($request, [
           'descr'      => 'required',
           'completed'  => 'required'
        ]);

        TaskList::create([
           'descr'      => $request->input('descr'),
           'completed'  => $request->input('completed')
        ]);

        return response(['message' => 'Task Created'], 200);
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
        $this->validate($request, [
            'descr'      => 'required',
            'completed'  => 'required'
        ]);

        $task = TaskList::find($id);

        $task->update($request->all());

        return response(['message' => 'Task Updated'], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = TaskList::find($id);
        $task->delete();
        return response(['message' => 'Task Deleted'], 200);
    }

    /**
     * Prvide JSON data to ajax calls
     *
     * @return \Illuminate\Http\Response
     */
    public function data()
    {
        $taskList = TaskList::all();
        return response($taskList, 200);
    }
}
