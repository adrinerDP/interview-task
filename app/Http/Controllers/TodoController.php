<?php

namespace App\Http\Controllers;

use App\Exports\TodosExport;
use App\Models\Todo;
use App\Services\Parser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::all();
        return response()->json($todos);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'title' => 'required|string',
            'completed' => 'required|boolean',
        ]);

        if ($validator->passes()) {
            return Todo::create($request->all());
        } else {
            return response()->json([
                'result' => '400 Bad Request',
                'message' => $validator->errors()->all(),
            ], 400);
        }
    }

    public function show(Todo $todo)
    {
        return $todo;
    }

    public function update(Request $request, Todo $todo)
    {
        $todo->update($request->all());
        return $todo;
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return response()->json([
            'result' => '200 Successful'
        ]);
    }

    public function download()
    {
        return Excel::download(new TodosExport, 'todos.csv');
    }

    public function search(Request $request)
    {
        if (!$request->exists('query')) {
            return response()->json([
                'result' => '400 Bad Request',
                'message' => ['The query field is required.']
            ], 400);
        }
        return Todo::search($this->parseSearchQuery($request->get('query')))->get();
    }

    private function parseSearchQuery($string)
    {
        return str_replace(
            ['equals', 'eq', 'neq'],
            ['=', '=', '!='],
            $string);
    }
}
