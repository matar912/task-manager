<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Autorisé si authentifié (géré par le middleware)
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status'      => ['nullable', Rule::in(Task::STATUSES)],
            'priority'    => ['nullable', Rule::in(Task::PRIORITIES)],
            'due_date'    => ['nullable', 'date', 'after:now'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'   => 'Le titre de la tâche est obligatoire.',
            'due_date.after'   => 'La date limite doit être dans le futur.',
            'category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
        ];
    }
}
