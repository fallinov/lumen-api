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
        return response()->json(Task::findOrFail($id));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'order' => 'required|integer|unique:task,order',
            'completed' => 'boolean',
            'due_date' => 'date_format:Y-m-d H:i:s' // ou simplement date
        ]);

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

    public function completed($id, Request $request)
    {
        $task = Task::findOrFail($id);
        $task->completed = 1;
        $task->update();

        return response()->json($task, 200);
    }

    public function unCompleted($id, Request $request)
    {
        $task = Task::findOrFail($id);
        $task->completed = 0;
        $task->update();

        return response()->json($task, 200);
    }
}