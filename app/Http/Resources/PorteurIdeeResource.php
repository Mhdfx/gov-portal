<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PorteurIdeeResource extends JsonResource
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
            'idea_title' => $this->idea_title,
            'idea_description' => $this->idea_description,
            'idea_sector' => $this->idea_sector,
            'idea_innovation_level' => $this->idea_innovation_level,
            'idea_target_market' => $this->idea_target_market,
            'idea_competitive_advantage' => $this->idea_competitive_advantage,
            'idea_business_model' => $this->idea_business_model,
            'idea_revenue_streams' => $this->idea_revenue_streams,
            'idea_funding_requirements' => $this->idea_funding_requirements,
            'idea_timeline' => $this->idea_timeline,
            'idea_team_size' => $this->idea_team_size,
            'idea_team_experience' => $this->idea_team_experience,
            'idea_previous_experience' => $this->idea_previous_experience,
            'idea_previous_experience_details' => $this->idea_previous_experience_details,
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