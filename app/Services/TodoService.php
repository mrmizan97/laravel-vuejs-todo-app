<?php

namespace App\Services;

use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class TodoService
{
    public function getAll()
    {
        return Auth::user()->todos;
    }

    public function create(array $data)
    {
        return Auth::user()->todos()->create($data);
    }

    public function update(Todo $todo, array $data)
    {
        $todo->update($data);
        return $todo;
    }

    public function delete(Todo $todo)
    {
        $todo->delete();
    }
}

