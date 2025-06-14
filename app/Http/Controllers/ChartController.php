<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function chart(Request $request)
    {
        $type = $request->query('type');

        if ($type === 'status') {
            // Status Summary
            $statuses = ['pending', 'open', 'in_progress', 'completed'];
            $summary = [];
            foreach ($statuses as $status) {
                $summary[$status] = Todo::where('status', $status)->count();
            }
            return response()->json(['status_summary' => $summary]);
        }

        if ($type === 'priority') {
            // Priority Summary
            $priorities = ['low', 'medium', 'high'];
            $summary = [];
            foreach ($priorities as $priority) {
                $summary[$priority] = Todo::where('priority', $priority)->count();
            }
            return response()->json(['priority_summary' => $summary]);
        }

        if ($type === 'assignee') {
            // Assignee Summary
            $assignees = Todo::distinct()->pluck('assignee');
            $assigneeSummary = [];
            foreach ($assignees as $assignee) {
                $totalTodos = Todo::where('assignee', $assignee)->count();
                $totalPending = Todo::where('assignee', $assignee)->where('status', 'pending')->count();
                $totalTimetrackedCompleted = Todo::where('assignee', $assignee)
                    ->where('status', 'completed')
                    ->sum('time_tracked');
                $assigneeSummary[$assignee] = [
                    'total_todos' => $totalTodos,
                    'total_pending_todos' => $totalPending,
                    'total_timetracked_completed_todos' => $totalTimetrackedCompleted,
                ];
            }
            return response()->json(['assignee_summary' => $assigneeSummary]);
        }

        // Unknown type
        return response()->json(['error' => 'Invalid chart type'], 400);
    }
}