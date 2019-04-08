<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function showAllTasks()
    {
        return response()->json(Task::all());
    }

    public function showOneTask($id)
    {
        return response()->json(Task::find($id));
    }

    public function create(Request $request)
    {
        $task = Task::create($request->all());

        return response()->json($task, 201);
    }

    public function update($id, Request $request)
    {
        $task = Task::findOrFail($id);
        $task->update($request->all());

        return response()->json($task, 200);
    }

    public function delete($id)
    {
        Task::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}