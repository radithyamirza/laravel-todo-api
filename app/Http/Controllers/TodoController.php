<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;

class TodoController extends Controller
{
    public function store(StoreTodoRequest $request): JsonResponse
    {
        $data = $request->validated();
        $todo = Todo::create($data);

        return response()->json($todo, 201);
    }
}