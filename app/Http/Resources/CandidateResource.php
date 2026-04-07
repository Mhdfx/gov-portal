<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_of_birth' => $this->date_of_birth?->format('Y-m-d'),
            'age' => $this->age,
            'gender' => $this->gender,
            'nationality' => $this->nationality,
            'address' => $this->address,
            'city' => $this->city,
            'region' => $this->region,
            'postal_code' => $this->postal_code,
            'education_level' => $this->education_level,
            'field_of_study' => $this->field_of_study,
            'university' => $this->university,
            'years_of_experience' => $this->years_of_experience,
            'skills' => $this->skills,
            'languages' => $this->languages,
            'professional_summary' => $this->professional_summary,
            'cv_url' => $this->cv_url,
            'cover_letter_url' => $this->cover_letter_url,
            'profile_picture_url' => $this->profile_picture_url,
            'availability' => $this->availability,
            'expected_salary' => $this->expected_salary,
            'preferred_job_type' => $this->preferred_job_type,
            'preferred_locations' => $this->preferred_locations,
            'preferred_sectors' => $this->preferred_sectors,
            'is_available' => $this->is_available,
            'is_verified' => $this->is_verified,
            'verified_at' => $this->verified_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}