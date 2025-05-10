<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Todo\StoreTodoRequest;
use App\Http\Requests\Todo\UpdateTodoRequest;
use App\Models\Todo;
use App\Services\TodoService;
use Throwable;

class TodoController extends Controller
{

    public function __construct(protected TodoService $todoService) {}

     public function index()
    {
        try {
            $todos = $this->todoService->getAll();
            return successResponse('Todo list fetched successfully.', $todos);
        } catch (Throwable $th) {
            return errorResponse($th->getMessage(), 500);
        }
    }

    public function store(StoreTodoRequest $request)
    {
        try {
            $todo = $this->todoService->create($request->all());
            return successResponse('Todo created successfully.', ['todo'=>$todo], 201);
        } catch (Throwable $th) {
            return errorResponse($th->getMessage(), 500);
        }
    }

    public function update(UpdateTodoRequest $request, Todo $todo)
    {
        try {
            $updatedTodo = $this->todoService->update($todo, $request->all());
            return successResponse('Todo updated successfully.', ['todo'=>$updatedTodo]);
        } catch (Throwable $th) {
            return errorResponse($th->getMessage(), 500);
        }
    }

    public function destroy(Todo $todo)
    {
        try {
            $this->todoService->delete($todo);
            return successResponse('Todo deleted successfully.', [], 204);
        } catch (Throwable $th) {
            return errorResponse($th->getMessage(), 500);
        }
    }}
