<?php

namespace App\Domain\Task\Repositories;

use App\Domain\Task\Models\Task;
use App\Domain\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class TaskRepository
{
    public function create(array $data, User $user): Task
    {
        $data = Arr::add($data, Task::USER_ID, $user->id);

        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);

        return $task;
    }

    public function findById($id): Task|null
    {
        return Task::find($id);
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    public function findOldIncompleteTasks(Carbon $date): Collection
    {
        return Task::whereDate(Task::CREATED_AT, '<=', $date)
            ->where(Task::COMPLITED, Task::INCOMPLITED_STATUS)
            ->get();
    }

    public function getAllUserTasks(User $user): Collection
    {
        return Task::where(Task::USER_ID, $user->id)->get();
    }
}
