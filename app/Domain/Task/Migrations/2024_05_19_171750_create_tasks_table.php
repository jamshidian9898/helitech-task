<?php

use App\Domain\Task\Models\Task;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(Task::ID);
            $table->unsignedBigInteger(Task::USER_ID);
            $table->string(Task::TITTLE);
            $table->text(Task::DESCRIPTION)->nullable();
            $table->boolean(Task::COMPLITED)->default(false);
            $table->timestamps();

            $table->foreign(Task::USER_ID)->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
