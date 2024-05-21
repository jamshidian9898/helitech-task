<?php

namespace App\Domain\Task\Models;

use App\Domain\Task\Factories\TaskFactory;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

class Task extends Model
{
    use HasFactory;

    // -------------- TABLE COLUMNS --------------
    const ID = 'id';
    const USER_ID = 'user_id';
    const TITTLE = 'title';
    const DESCRIPTION = 'description';
    const COMPLITED = 'completed';

    // -------------- COMPLITED STATUSES --------------
    const COMPLITED_STATUS = true;
    const INCOMPLITED_STATUS = false;

    protected $fillable = [
        self::USER_ID, self::TITTLE, self::DESCRIPTION, self::COMPLITED,
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return TaskFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
