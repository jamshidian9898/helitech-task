<?php

namespace App\Http\Controllers\API;

use App\Domain\Task\Exceptions\notFoundTaskException;
use App\Domain\Task\Services\TaskService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\CreateNewTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\Task\TaskResource;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        $tasks = $this->taskService->getAllUserTasks(auth()->user());

        return response()->json(TaskResource::collection($tasks));
    }

    public function store(CreateNewTaskRequest $request)
    {
        $task = $this->taskService->createTask($request->validated(), auth()->user());

        return response()->json(new TaskResource($task), 201);
    }

    public function show($id)
    {
        $this->taskService->hasUserAccess($id, auth()->user());

        $task = $this->taskService->findById($id);

        if (is_null($task))
            throw new notFoundTaskException('not found task.');

        return response()->json(new TaskResource($task));
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $this->taskService->hasUserAccess($id, auth()->user());

        $task = $this->taskService->updateTask($id, $request->validated());

        return response()->json(new TaskResource($task));
    }

    public function destroy($id)
    {
        $this->taskService->hasUserAccess($id, auth()->user());

        $this->taskService->deleteTask($id);

        return response()->json(null, 204);
    }
}
