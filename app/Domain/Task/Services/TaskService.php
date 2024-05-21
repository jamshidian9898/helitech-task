<?php

namespace App\Domain\Task\Services;

use App\Domain\Task\Exceptions\notFoundTaskException;
use App\Domain\Task\Models\Task;
use App\Domain\Task\Repositories\TaskRepository;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function hasUserAccess($id, User $user): void
    {
        $task = $this->taskRepository->findById($id);

        if (is_null($task))
            throw new notFoundTaskException('not found task.');

        if ($task->user_id != $user->id)
            throw new notFoundTaskException('not found task.');
    }

    public function getAllUserTasks(User $user): Collection
    {
        return $this->taskRepository->getAllUserTasks($user);
    }

    public function createTask(array $data, User $user): Task
    {
        return $this->taskRepository->create($data, $user);
    }

    public function updateTask($id, array $data): Task
    {
        $task = $this->taskRepository->findById($id);

        if (is_null($task))
            throw new notFoundTaskException('not found task.');

        return $this->taskRepository->update($task, $data);
    }

    public function deleteTask($id): void
    {
        $task = $this->taskRepository->findById($id);

        $this->taskRepository->delete($task);
    }

    public function findById($id): Task|null
    {
        return $this->taskRepository->findById($id);
    }
}
