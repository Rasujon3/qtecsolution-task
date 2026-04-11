<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'status'      => 'required|in:pending,in_progress,completed',
            'priority'    => 'required|in:low,medium,high',
            'due_date'    => 'nullable|date|after_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Task title is required.',
            'title.max'            => 'Task title cannot exceed 255 characters.',
            'status.required'      => 'Please select a status.',
            'status.in'            => 'Invalid status selected.',
            'priority.required'    => 'Please select a priority.',
            'priority.in'          => 'Invalid priority selected.',
            'due_date.date'        => 'Please enter a valid date.',
            'due_date.after_or_equal' => 'Due date cannot be in the past.',
            'description.max'      => 'Description cannot exceed 2000 characters.',
        ];
    }
}
