<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class INDHResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'project_title' => $this->project_title,
            'project_description' => $this->project_description,
            'project_sector' => $this->project_sector,
            'project_type' => $this->project_type,
            'project_location' => $this->project_location,
            'project_beneficiaries' => $this->project_beneficiaries,
            'project_duration' => $this->project_duration,
            'project_budget' => $this->project_budget,
            'project_funding_requested' => $this->project_funding_requested,
            'project_team_size' => $this->project_team_size,
            'project_team_experience' => $this->project_team_experience,
            'project_previous_experience' => $this->project_previous_experience,
            'project_previous_experience_details' => $this->project_previous_experience_details,
            'status' => $this->status,
            'tracking_number' => $this->tracking_number,
            'submitted_at' => $this->submitted_at?->format('Y-m-d H:i:s'),
            'reviewed_at' => $this->reviewed_at?->format('Y-m-d H:i:s'),
            'reviewed_by' => $this->reviewed_by,
            'reviewer' => new UserResource($this->whenLoaded('reviewer')),
            'admin_notes' => $this->admin_notes,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}