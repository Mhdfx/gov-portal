<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentResource extends JsonResource
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
            'investment_type' => $this->investment_type,
            'investment_amount' => $this->investment_amount,
            'investment_sector' => $this->investment_sector,
            'investment_location' => $this->investment_location,
            'investment_duration' => $this->investment_duration,
            'investment_expected_return' => $this->investment_expected_return,
            'investment_risk_level' => $this->investment_risk_level,
            'investment_description' => $this->investment_description,
            'investment_business_plan' => $this->investment_business_plan,
            'investment_financial_projections' => $this->investment_financial_projections,
            'investment_market_analysis' => $this->investment_market_analysis,
            'investment_team_experience' => $this->investment_team_experience,
            'investment_previous_experience' => $this->investment_previous_experience,
            'investment_previous_experience_details' => $this->investment_previous_experience_details,
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