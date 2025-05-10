<?php
namespace Tests\Feature;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    public function test_user_can_create_todo()
    {
        $response = $this->postJson('/api/todos', [
            'title' => 'Test Todo',
            'body' => 'This is a test todo',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'title' => 'Test Todo',
                'body' => 'This is a test todo',
            ]);

        $this->assertDatabaseHas('todos', ['title' => 'Test Todo']);
    }

    public function test_user_can_get_todos()
    {
        Todo::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Sample Todo',
        ]);

        $response = $this->getJson('/api/todos');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Sample Todo']);
    }

    public function test_user_can_update_todo()
    {
        $todo = Todo::factory()->create([
            'user_id' => $this->user->id,
            'completed' => false,
        ]);

        $response = $this->putJson("/api/todos/{$todo->id}", [
            'title' => 'Updated Title',
            'completed' => true,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Updated Title', 'completed' => true]);

        $this->assertDatabaseHas('todos', ['title' => 'Updated Title', 'completed' => true]);
    }

    public function test_user_can_delete_todo()
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/todos/{$todo->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }

    public function test_validation_fails_on_create()
    {
        $response = $this->postJson('/api/todos', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'body']);
    }
    public function test_todo_creation_fails_when_title_is_missing()
    {
        $response = $this->postJson('/api/todos', [
            'body' => 'Some valid body'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }
    public function test_todo_creation_with_valid_data()
    {
        $response = $this->postJson('/api/todos', [
            'title' => 'Finish project',
            'body' => 'Complete the API integration by Monday.'
        ]);

        $response->assertStatus(201); // Adjust if your controller returns 200
    }
    public function test_todo_creation_fails_when_body_is_missing()
    {
        $response = $this->postJson('/api/todos', [
            'title' => 'Some valid title'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['body']);
    }
    public function test_todo_creation_fails_when_title_is_too_long()
    {
        $response = $this->postJson('/api/todos', [
            'title' => str_repeat('a', 256), // 1 over the limit
            'body' => 'Valid body'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_todo_creation_fails_with_non_string_fields()
    {
        $response = $this->postJson('/api/todos', [
            'title' => 123,
            'body' => ['invalid', 'array']
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'body']);
    }


    public function test_todo_creation_succeeds_with_all_valid_fields()
    {
        $response = $this->postJson('/api/todos', [
            'title' => 'Test Title',
            'body' => 'This is a test body.',
            'completed' => true
        ]);

        $response->assertStatus(201); // or 200 based on your controller
    }
    public function test_todo_creation_succeeds_with_no_optional_fields()
    {
        $response = $this->postJson('/api/todos', []); // all are optional

        $response->assertStatus(201); // or 200
    }
    public function test_todo_creation_fails_when_title_is_empty()
    {
        $response = $this->postJson('/api/todos', [
            'title' => ''
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }
    public function test_todo_creation_fails_when_body_is_not_a_string()
    {
        $response = $this->postJson('/api/todos', [
            'body' => ['not', 'a', 'string']
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['body']);
    }
    public function test_todo_creation_fails_when_completed_is_not_boolean()
    {
        $response = $this->postJson('/api/todos', [
            'completed' => 'yes'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['completed']);
    }

}


