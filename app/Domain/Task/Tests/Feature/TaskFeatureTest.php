<?php

namespace App\Domain\Task\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Domain\Task\Models\Task;
use App\Domain\User\Models\User;

class TaskFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_create_task()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/tasks', [
            'title' => 'Test Task',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'title' => 'Test Task',
                'completed' => false,
            ]);
    }

    public function test_user_can_not_create_task_without_title()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/tasks', [
            'description' => 'Test Task',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'title'
                ]
            ]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/tasks', [
            'title' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'title'
                ]
            ]);
    }

    public function test_user_can_update_own_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task',
            'description' => 'Updated Task Description',
            'completed' => true,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Updated Task',
                'description' => 'Updated Task Description',
                'completed' => true,
            ]);
    }

    public function test_user_cannot_update_other_users_tasks()
    {
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        $task = Task::factory()->create(['user_id' => $firstUser->id]);

        $response = $this->actingAs($secondUser, 'sanctum')->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task',
            'completed' => true,
        ]);

        $response->assertStatus(404)
            ->assertJsonStructure(['message']);
    }

    public function test_user_can_delete_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_user_cannot_delete_other_users_tasks()
    {
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        $task = Task::factory()->create(['user_id' => $firstUser->id]);

        $response = $this->actingAs($secondUser, 'sanctum')->deleteJson("/api/tasks/{$task->id}");

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);

        $response->assertStatus(404)->assertJsonStructure(['message']);
    }

    public function test_user_can_view_tasks()
    {
        $user = User::factory()->create();
        Task::factory()->count(5)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    public function test_user_cannot_view_other_users_tasks()
    {
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        $tasks = Task::factory()->count(5)->create(['user_id' => $firstUser->id]);

        $response = $this->actingAs($secondUser, 'sanctum')->getJson("/api/tasks/{$tasks->random()->id}");

        $response->assertStatus(404)->assertJsonStructure(['message']);
    }
}
