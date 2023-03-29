<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskCreateRequest;
use App\Http\Traits\ApiResponder;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use ApiResponder;

    /**
     * Store a newly created resource in storage.
     * @param TaskCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TaskCreateRequest $request)
    {
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'order' => Task::max('order') + 1,
            'status_id' => $request->status_id
        ]);

        return $this->success($task, 'Task Creates Successfully', 201);
    }

    /**
     * Get all tasks
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tasks = Status::select('id', 'title', 'slug', 'order')
            ->with(['tasks' => function ($query) {
                $query->select('id', 'title', 'description', 'order', 'status_id');
            }])
            ->get();
        return $this->success($tasks, 'Tasks Retrieved Successfully', 200);
    }


    /**
     * Drag and Drop task
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync(Request $request, $id)
    {
        $task = Task::find($id);
        $task->status_id = $request->status_id;
        $task->order = Task::where('status_id', $request->status_id)->max('order') + 1;
        $task->save();
        return $this->success($task, 'Task Updated Successfully', 200);
    }

}
