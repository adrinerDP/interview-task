<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Todo {
    protected string $url = "https://jsonplaceholder.typicode.com/todos";

    public function getTodoItems() : Collection
    {
        $response = Http::get($this->url);
        return collect($response->json());
    }

    public function updateTodoItemsToDatabase(Collection $todos) : void
    {
        $todos = $this->matchColumnNameToConvention($todos);
        \App\Models\Todo::insert($todos->toArray());
    }

    public function checkIfHasItems() : bool
    {
        return \App\Models\Todo::all()->count() > 0;
    }

    public function truncateItems() : void
    {
        \App\Models\Todo::truncate();
    }

    protected function matchColumnNameToConvention($todos) : Collection
    {
        return $todos->map(function ($todo) {
            $todo['user_id'] = $todo['userId'];
            unset($todo['id'], $todo['userId']);
            return $todo;
        });
    }
}
