<?php

namespace App\Http\Controllers\API;

use App\Domain\Task\Exceptions\notFoundTaskException;
use App\Domain\Task\Services\TaskService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\CreateNewTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\Task\TaskResource;

/**
 * @OA\Info(
 *     title="Task manager",
 *     version="1.0.0",
 *     description="Tasks api endpoints"
 * )
 */
class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="get list of tasks",
     *     @OA\Response(
     *         response=200,
     *         description="list of tasks",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TaskResource")
     *         )
     *     ),
     * )
     */
    public function index()
    {
        $tasks = $this->taskService->getAllUserTasks(auth()->user());

        return response()->json(TaskResource::collection($tasks));
    }

    /**
     * @OA\Post(
     *     path="/api/Tasks",
     *     summary="Create a new task",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateNewTaskRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created",
     *         @OA\JsonContent(ref="#/components/schemas/TaskResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(CreateNewTaskRequest $request)
    {
        $task = $this->taskService->createTask($request->validated(), auth()->user());

        return response()->json(new TaskResource($task), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/Tasks/{id}",
     *     summary="Show an exists task",
     *     @OA\Response(
     *         response=200,
     *         description="Show Task",
     *         @OA\JsonContent(ref="#/components/schemas/TaskResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="notFound provided task."
     *     )
     * )
     */
    public function show($id)
    {
        $this->taskService->hasUserAccess($id, auth()->user());

        $task = $this->taskService->findById($id);

        if (is_null($task))
            throw new notFoundTaskException('not found task.');

        return response()->json(new TaskResource($task));
    }

    /**
     * @OA\Put(
     *     path="/api/Tasks/{id}",
     *     summary="update an exists task",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTaskRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="update Task",
     *         @OA\JsonContent(ref="#/components/schemas/TaskResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="notFound provided task."
     *     )
     * )
     */
    public function update(UpdateTaskRequest $request, $id)
    {
        $this->taskService->hasUserAccess($id, auth()->user());

        $task = $this->taskService->updateTask($id, $request->validated());

        return response()->json(new TaskResource($task));
    }

    /**
     * @OA\Delete(
     *     path="/api/Tasks/{id}",
     *     summary="delete an exists task",
     *     @OA\Response(
     *         response=204,
     *         description="delete Task",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="notFound provided task."
     *     )
     * )
     */
    public function destroy($id)
    {
        $this->taskService->hasUserAccess($id, auth()->user());

        $this->taskService->deleteTask($id);

        return response()->json(null, 204);
    }
}
