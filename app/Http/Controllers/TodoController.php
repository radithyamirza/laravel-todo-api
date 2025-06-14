<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TodoController extends Controller
{
    public function store(StoreTodoRequest $request): JsonResponse
    {
        $data = $request->validated();
        $todo = Todo::create($data);

        return response()->json($todo, 201);
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $query = Todo::query();

        // Filtering
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        if ($request->filled('assignee')) {
            $assignees = array_map('trim', explode(',', $request->assignee));
            $query->whereIn('assignee', $assignees);
        }
        // Add more filters as needed...

        $todos = $query->get(['title', 'assignee', 'due_date', 'time_tracked', 'status', 'priority']);

        // Prepare data for Excel
        $rows = $todos->map(function ($todo) {
            return [
                'title' => $todo->title,
                'assignee' => $todo->assignee,
                'due_date' => $todo->due_date,
                'time_tracked' => $todo->time_tracked,
                'status' => $todo->status,
                'priority' => $todo->priority,
            ];
        })->toArray();

        // Add summary row
        $rows[] = [
            'title' => 'Total',
            'assignee' => '',
            'due_date' => '',
            'time_tracked' => $todos->sum('time_tracked'),
            'status' => '',
            'priority' => 'Count: ' . $todos->count(),
        ];

        // Generate Excel
        return Excel::download(new class($rows) implements \Maatwebsite\Excel\Concerns\FromArray {
            private $rows;
            public function __construct($rows) { $this->rows = $rows; }
            public function array(): array { return array_merge([['title','assignee','due_date','time_tracked','status','priority']], $this->rows); }
        }, 'todos.xlsx');
    }
}