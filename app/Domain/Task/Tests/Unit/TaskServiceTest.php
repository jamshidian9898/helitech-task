<?php

namespace App\Domain\Task\Tests\Unit;

use Tests\TestCase;
use App\Domain\Task\Services\TaskService;
use App\Domain\Task\Repositories\TaskRepository;
use App\Domain\Task\Models\Task;
use App\Domain\User\Models\User;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_task()
    {
        $taskRepository = Mockery::mock(TaskRepository::class);
        $taskService = new TaskService($taskRepository);

        $data = ['title' => 'Test Task', 'user_id' => 1];
        $user = new User(['id' => 1]);
        $taskRepository->shouldReceive('create')->with($data, $user)->andReturn(new Task($data));

        $task = $taskService->createTask($data, $user);

        $this->assertEquals('Test Task', $task->title);
        $this->assertEquals(1, $task->user_id);
    }

    public function test_update_task()
    {
        $taskRepository = Mockery::mock(TaskRepository::class);
        $taskService = new TaskService($taskRepository);

        $task = new Task(['title' => 'Old Task', 'completed' => false]);
        $data = ['title' => 'Updated Task', 'completed' => true];
        $taskRepository->shouldReceive('findById')->with(1)->andReturn($task);
        $taskRepository->shouldReceive('update')->with($task, $data)->andReturn($task->fill($data));

        $updatedTask = $taskService->updateTask(1, $data);

        $this->assertEquals('Updated Task', $updatedTask->title);
        $this->assertTrue($updatedTask->completed);
    }
}
