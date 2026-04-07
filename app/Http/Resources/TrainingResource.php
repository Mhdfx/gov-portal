<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingResource extends JsonResource
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
            'training_type' => $this->training_type,
            'training_title' => $this->training_title,
            'training_description' => $this->training_description,
            'training_sector' => $this->training_sector,
            'training_duration' => $this->training_duration,
            'training_location' => $this->training_location,
            'training_participants' => $this->training_participants,
            'training_budget' => $this->training_budget,
            'training_funding_requested' => $this->training_funding_requested,
            'training_team_size' => $this->training_team_size,
            'training_team_experience' => $this->training_team_experience,
            'training_previous_experience' => $this->training_previous_experience,
            'training_previous_experience_details' => $this->training_previous_experience_details,
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