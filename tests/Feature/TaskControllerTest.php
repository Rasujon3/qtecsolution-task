<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    // =====================
    // INDEX TEST
    // =====================

    /** @test */
    public function task_index_page_loads_successfully()
    {
        $response = $this->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.tasks.index');
    }

    // =====================
    // CREATE TEST
    // =====================

    /** @test */
    public function task_create_page_loads_successfully()
    {
        $response = $this->get(route('tasks.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.tasks.create');
    }

    // =====================
    // STORE TESTS
    // =====================

    /** @test */
    public function task_can_be_created_with_valid_data()
    {
        $data = [
            'title'       => 'Test Task',
            'description' => 'Test description',
            'status'      => 'pending',
            'priority'    => 'medium',
            'due_date'    => now()->addDays(5)->format('Y-m-d'),
        ];

        $response = $this->post(route('tasks.store'), $data);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['title' => 'Test Task']);
    }

    /** @test */
    public function task_can_be_created_without_optional_fields()
    {
        $data = [
            'title'    => 'Minimal Task',
            'status'   => 'pending',
            'priority' => 'low',
        ];

        $response = $this->post(route('tasks.store'), $data);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['title' => 'Minimal Task']);
    }

    /** @test */
    public function task_store_fails_when_title_is_missing()
    {
        $data = [
            'status'   => 'pending',
            'priority' => 'medium',
        ];

        $response = $this->post(route('tasks.store'), $data);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseCount('tasks', 0);
    }

    /** @test */
    public function task_store_fails_when_status_is_invalid()
    {
        $data = [
            'title'    => 'Test Task',
            'status'   => 'invalid_status',
            'priority' => 'medium',
        ];

        $response = $this->post(route('tasks.store'), $data);

        $response->assertSessionHasErrors('status');
    }

    /** @test */
    public function task_store_fails_when_priority_is_invalid()
    {
        $data = [
            'title'    => 'Test Task',
            'status'   => 'pending',
            'priority' => 'urgent',
        ];

        $response = $this->post(route('tasks.store'), $data);

        $response->assertSessionHasErrors('priority');
    }

    /** @test */
    public function task_store_fails_when_due_date_is_in_past()
    {
        $data = [
            'title'    => 'Test Task',
            'status'   => 'pending',
            'priority' => 'medium',
            'due_date' => now()->subDays(1)->format('Y-m-d'),
        ];

        $response = $this->post(route('tasks.store'), $data);

        $response->assertSessionHasErrors('due_date');
    }

    // =====================
    // SHOW / EDIT TEST
    // =====================

    /** @test */
    public function task_edit_page_loads_with_correct_task()
    {
        $task = Task::factory()->create();

        $response = $this->get(route('tasks.show', $task->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.tasks.edit');
        $response->assertViewHas('task', $task);
    }

    // =====================
    // UPDATE TESTS
    // =====================

    /** @test */
    public function task_can_be_updated_with_valid_data()
    {
        $task = Task::factory()->create(['title' => 'Old Title']);

        $data = [
            'title'    => 'Updated Title',
            'status'   => 'in_progress',
            'priority' => 'high',
        ];

        $response = $this->put(route('tasks.update', $task->id), $data);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['title' => 'Updated Title']);
        $this->assertDatabaseMissing('tasks', ['title' => 'Old Title']);
    }

    /** @test */
    public function task_update_fails_when_title_is_missing()
    {
        $task = Task::factory()->create();

        $data = [
            'status'   => 'completed',
            'priority' => 'low',
        ];

        $response = $this->put(route('tasks.update', $task->id), $data);

        $response->assertSessionHasErrors('title');
    }

    // =====================
    // DESTROY TEST
    // =====================

    /** @test */
    public function task_can_be_deleted()
    {
        $task = Task::factory()->create();

        $response = $this->delete(route('tasks.destroy', $task->id));

        $response->assertStatus(200);
        $response->assertJson(['status' => true]);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function deleting_nonexistent_task_returns_404()
    {
        $response = $this->delete(route('tasks.destroy', 9999));

        $response->assertStatus(404);
    }

    // =====================
    // STATUS UPDATE TEST
    // =====================

    /** @test */
    public function task_status_can_be_updated_via_ajax()
    {
        $task = Task::factory()->create(['status' => 'pending']);

        $response = $this->postJson(route('tasks.statusUpdate'), [
            'id'     => $task->id,
            'status' => 'completed',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => true]);
        $this->assertDatabaseHas('tasks', [
            'id'     => $task->id,
            'status' => 'completed',
        ]);
    }

    /** @test */
    public function task_status_update_fails_with_invalid_status()
    {
        $task = Task::factory()->create(['status' => 'pending']);

        $response = $this->postJson(route('tasks.statusUpdate'), [
            'id'     => $task->id,
            'status' => 'wrong_status',
        ]);

        $response->assertStatus(422);
    }
}
