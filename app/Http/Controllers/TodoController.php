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