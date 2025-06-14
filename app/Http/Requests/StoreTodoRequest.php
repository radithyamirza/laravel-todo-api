<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTodoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $today = now()->toDateString();

        return [
            'title' => ['required', 'string'],
            'assignee' => ['nullable', 'string'],
            'due_date' => ['required', 'date', 'after_or_equal:' . $today],
            'time_tracked' => ['nullable', 'numeric'],
            'status' => [
                'nullable',
                Rule::in(['pending', 'open', 'in_progress', 'completed'])
            ],
            'priority' => [
                'required',
                Rule::in(['low', 'medium', 'high'])
            ],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'status' => $this->status ?? 'pending',
            'time_tracked' => $this->time_tracked ?? 0,
        ]);
    }
}