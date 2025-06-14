<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TodoController extends Controller
{
    public function store(Request $request)
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

        $todo = Todo::create($validated);

        return response()->json($todo, 201);
    }
}