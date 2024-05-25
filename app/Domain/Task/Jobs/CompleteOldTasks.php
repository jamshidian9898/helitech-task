<?php

namespace App\Domain\Task\Jobs;

use App\Domain\Task\Events\TaskUpdated;
use App\Domain\Task\Repositories\TaskRepository;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CompleteOldTasks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $taskRepository;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->taskRepository = new TaskRepository;
    }


    /**
     * Execute the job.
     */
    public function handle()
    {
        $tasks = $this->taskRepository->findOldIncompleteTasks(Carbon::now()->subDays(2));
        foreach ($tasks as $task) {
            $this->taskRepository->update($task, ['completed' => true]);
            broadcast(new TaskUpdated($task));
        }
    }
}
