<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use App\Exports\TodosExport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class TodoController extends Controller
{
    public function store(StoreTodoRequest $request): JsonResponse
    {
         $validated = $request->validate([
            'title' => 'required|string',
            'assignee' => 'nullable|string',
            'due_date' => 'required|date|after_or_equal:today',
            'time_tracked' => 'nullable|numeric',
            'status' => ['nullable', Rule::in(['pending', 'open', 'in_progress', 'completed'])],
            'priority' => ['nullable', Rule::in(['low', 'medium', 'high'])],
        ]);

        $validated['status'] = $validated['status'] ?? 'pending';
        $validated['time_tracked'] = $validated['time_tracked'] ?? 0;
        $validated['priority'] = $validated['priority'] ?? 'medium';
        $validated['assignee'] = $validated['assignee'] ?? 'unassigned';
        $validated['due_date'] = date('Y-m-d', strtotime($validated['due_date']));
        $validated['time_tracked'] = $validated['time_tracked'] ?: 0.0;

        $todo = Todo::create($validated);

        return response()->json($todo, 201);
    }

    public function exportExcel(Request $request)
    {
        $query = Todo::query();

        // title: <String> (partial match)
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // assignee: <multiple Strings separated by commas>
        if ($request->filled('assignee')) {
            $assignees = array_map('trim', explode(',', $request->assignee));
            $query->whereIn('assignee', $assignees);
        }

        // due_date: <range of dates> (e.g., start=YYYY-MM-DD&end=YYYY-MM-DD)
        if ($request->filled('due_date_start')) {
            $query->whereDate('due_date', '>=', $request->due_date_start);
        }   
        if ($request->filled('due_date_end')) {
            $query->whereDate('due_date', '<=', $request->due_date_end);
        }

        // time_tracked: <range of numeric values> (e.g., min=X&max=Y)
        if ($request->filled('time_tracked_min')) {
            $query->where('time_tracked', '>=', $request->time_tracked_min);
        }
        if ($request->filled('time_tracked_max')) {
            $query->where('time_tracked', '<=', $request->time_tracked_max);
        }

        // status: <multiple Strings separated by commas>
        if ($request->filled('status')) {
            $statuses = array_map('trim', explode(',', $request->status));
            $query->whereIn('status', $statuses);
        }

        // priority: <multiple Strings separated by commas>
        if ($request->filled('priority')) {
            $priorities = array_map('trim', explode(',', $request->priority));
            $query->whereIn('priority', $priorities);
        }

        $todos = $query->get();

        return Excel::download(new TodosExport($todos), 'todos.xlsx');
    }
}