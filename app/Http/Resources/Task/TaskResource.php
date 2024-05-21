<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="TaskResource",
 *     type="object",
 *     required={"id","title","description","completed","created_at"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Task ID",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Task's title",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Task's description",
 *     ),
 *     @OA\Property(
 *         property="completed",
 *         type="boolean",
 *         description="Task's completed",
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="HH:mm:ss",
 *         description="Task's created_at",
 *     )
 * )
 */
class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'completed' => $this->completed,
            'created_at' => $this->created_at
        ];
    }
}
