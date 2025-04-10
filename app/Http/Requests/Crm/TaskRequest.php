<?php

declare(strict_types=1);

namespace App\Http\Requests\Crm;

use App\Models\Crm\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => [
                'nullable',
                Rule::in(array_keys(Task::getStatusOptions()))
            ],
            'priority' => [
                'nullable',
                Rule::in(array_keys(Task::getPriorityOptions()))
            ],
            'due_date' => ['nullable', 'date'],
            'company_id' => ['nullable', 'integer', 'exists:crm_companies,id'],
            'contact_id' => ['nullable', 'integer', 'exists:crm_contacts,id'],
            'opportunity_id' => ['nullable', 'integer', 'exists:crm_opportunities,id'],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'company_id' => 'company',
            'contact_id' => 'contact',
            'opportunity_id' => 'opportunity',
            'assigned_to' => 'assignee',
        ];
    }
} 