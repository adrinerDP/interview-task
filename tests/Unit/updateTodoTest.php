<?php

namespace Tests\Unit;

use App\Services\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class updateTodoTest extends TestCase
{
    use RefreshDatabase;

    public function testIfInsertOnceIsValid()
    {
        // given
        $todoService = new Todo();
        $todos = $todoService->getTodoItems();

        // when
        $todoService->updateTodoItemsToDatabase($todos);

        // then
        $this->assertDatabaseCount('todos', $todos->count());
    }

    public function testIfInsertTwiceIsValidWithTruncating()
    {
        // given
        $todoService = new Todo();
        $todos = $todoService->getTodoItems();

        // when
        $todoService->updateTodoItemsToDatabase($todos);
        $this->artisan('todo:update')
            ->expectsConfirmation('Do you want to truncate current data?', 'yes');

        // then
        $this->assertDatabaseCount('todos', $todos->count());
    }

    public function testIfInsertTwiceIsValidWithoutTruncating()
    {
        // given
        $todoService = new Todo();
        $todos = $todoService->getTodoItems();

        // when
        $todoService->updateTodoItemsToDatabase($todos);
        $this->artisan('todo:update')
            ->expectsConfirmation('Do you want to truncate current data?');

        // then
        $this->assertDatabaseCount('todos', $todos->count() * 2);
    }
}
