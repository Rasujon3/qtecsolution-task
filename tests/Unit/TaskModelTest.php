<?php

namespace Tests\Unit;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function task_has_correct_fillable_fields()
    {
        $fillable = ['title', 'description', 'status', 'priority', 'due_date'];

        $this->assertEquals($fillable, (new Task())->getFillable());
    }

    /** @test */
    public function task_can_be_created_with_factory()
    {
        $task = Task::factory()->create();

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function task_status_is_one_of_valid_values()
    {
        $validStatuses = ['pending', 'in_progress', 'completed'];

        $task = Task::factory()->create(['status' => 'pending']);

        $this->assertContains($task->status, $validStatuses);
    }

    /** @test */
    public function task_priority_is_one_of_valid_values()
    {
        $validPriorities = ['low', 'medium', 'high'];

        $task = Task::factory()->create(['priority' => 'high']);

        $this->assertContains($task->priority, $validPriorities);
    }

    /** @test */
    public function task_description_can_be_null()
    {
        $task = Task::factory()->create(['description' => null]);

        $this->assertNull($task->description);
    }

    /** @test */
    public function task_due_date_can_be_null()
    {
        $task = Task::factory()->create(['due_date' => null]);

        $this->assertNull($task->due_date);
    }
}
