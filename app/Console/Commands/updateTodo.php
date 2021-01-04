<?php

namespace App\Console\Commands;

use App\Services\Todo;
use Illuminate\Console\Command;

class updateTodo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todo:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update todo list from the resource';

    /**
     * Todo Service
     *
     * @var Todo
     */
    protected Todo $todo;

    /**
     * Create a new command instance.
     *
     * @param Todo $todo
     */
    public function __construct(Todo $todo)
    {
        parent::__construct();
        $this->todo = $todo;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() : void
    {
        if ($this->todo->checkIfHasItems()) {
            if ($this->confirm('Do you want to truncate current data?')) {
                $this->todo->truncateItems();
            }
        }
        $todos = $this->todo->getTodoItems();
        $this->todo->updateTodoItemsToDatabase($todos);
        $this->line("Updated todo list successfully.");
    }
}
