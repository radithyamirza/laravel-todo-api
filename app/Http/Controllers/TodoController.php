<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use App\Exports\TodosExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TodoController extends Controller
{
    public function store(StoreTodoRequest $request): JsonResponse
    {
        $data = $request->validated();
        $todo = Todo::create($data);

        return response()->json($todo, 201);
    }

    public function exportExcel(Request $request)
    {
        $query = Todo::query();

        // Example filtering, adjust as needed
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // Add your other filters here, like assignee, due_date, etc.

        $todos = $query->get();

        return Excel::download(new TodosExport($todos), 'todos.xlsx');
    }
}