<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AutoEntrepreneurResource extends JsonResource
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
            'business_name' => $this->business_name,
            'business_type' => $this->business_type,
            'business_description' => $this->business_description,
            'business_address' => $this->business_address,
            'business_city' => $this->business_city,
            'business_region' => $this->business_region,
            'business_phone' => $this->business_phone,
            'business_email' => $this->business_email,
            'business_website' => $this->business_website,
            'business_sector' => $this->business_sector,
            'business_activity' => $this->business_activity,
            'business_legal_form' => $this->business_legal_form,
            'business_registration_number' => $this->business_registration_number,
            'business_tax_number' => $this->business_tax_number,
            'business_start_date' => $this->business_start_date?->format('Y-m-d'),
            'business_capital' => $this->business_capital,
            'business_employees_count' => $this->business_employees_count,
            'business_turnover' => $this->business_turnover,
            'business_profit' => $this->business_profit,
            'business_challenges' => $this->business_challenges,
            'business_opportunities' => $this->business_opportunities,
            'business_goals' => $this->business_goals,
            'business_needs' => $this->business_needs,
            'business_support_requested' => $this->business_support_requested,
            'business_support_type' => $this->business_support_type,
            'business_support_amount' => $this->business_support_amount,
            'business_support_description' => $this->business_support_description,
            'business_support_timeline' => $this->business_support_timeline,
            'business_support_expected_outcome' => $this->business_support_expected_outcome,
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