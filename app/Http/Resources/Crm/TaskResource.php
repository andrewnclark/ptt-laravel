<?php

declare(strict_types=1);

namespace App\Http\Resources\Crm;

use App\Models\Crm\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Task $this */
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date?->format('Y-m-d'),
            'completed_at' => $this->completed_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'company_id' => $this->company_id,
            'contact_id' => $this->contact_id,
            'opportunity_id' => $this->opportunity_id,
            'user_id' => $this->user_id,
            'assigned_to' => $this->assigned_to,
            
            // Include relationships when loaded
            'company' => $this->whenLoaded('company', fn() => new CompanyResource($this->company)),
            'contact' => $this->whenLoaded('contact', fn() => new ContactResource($this->contact)),
            'opportunity' => $this->whenLoaded('opportunity', fn() => new OpportunityResource($this->opportunity)),
            'user' => $this->whenLoaded('user', fn() => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ]),
            'assignee' => $this->whenLoaded('assignedTo', fn() => [
                'id' => $this->assignedTo->id,
                'name' => $this->assignedTo->name,
            ]),
            
            // Include status and priority text
            'status_text' => Task::getStatusOptions()[$this->status] ?? null,
            'priority_text' => Task::getPriorityOptions()[$this->priority] ?? null,
        ];
    }
} 