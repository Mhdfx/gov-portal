<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrainingSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('user');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Personal Information
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'nationality' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'region' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            
            // Training Information
            'training_title' => 'required|string|max:255',
            'training_description' => 'required|string|max:5000',
            'training_type' => 'required|string|in:technical,business,soft_skills,certification,workshop,seminar,language,digital_skills,entrepreneurship,autre',
            'training_category' => 'required|string|in:professional_development,skill_enhancement,career_change,compliance,personal_growth,autre',
            'target_audience' => 'required|string|max:2000',
            'participant_count' => 'required|integer|min:1|max:500',
            'duration_hours' => 'required|integer|min:1|max:200',
            'training_format' => 'required|string|in:in_person,online,hybrid',
            'language_preference' => 'required|string|in:french,arabic,english',
            
            // Location & Schedule
            'preferred_location' => 'nullable|string|max:255',
            'preferred_schedule' => 'nullable|string|max:1000',
            'flexible_schedule' => 'nullable|boolean',
            'start_date_preference' => 'nullable|date|after:today',
            'end_date_preference' => 'nullable|date|after:start_date_preference',
            
            // Financial Information
            'budget_available' => 'nullable|numeric|min:0|max:999999999',
            'budget_currency' => 'required_with:budget_available|string|in:MAD,EUR,USD',
            'funding_source' => 'nullable|string|in:personal,employer,government,scholarship,autre',
            'payment_plan' => 'nullable|string|max:1000',
            
            // Requirements & Expectations
            'specific_requirements' => 'nullable|string|max:2000',
            'learning_objectives' => 'required|string|max:2000',
            'expected_outcomes' => 'required|string|max:2000',
            'certification_needed' => 'nullable|boolean',
            'certification_type' => 'nullable|string|max:500',
            
            // Documents
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'motivation_letter' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'previous_certificates' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'employer_approval' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            
            // Additional Information
            'motivation' => 'required|string|max:2000',
            'previous_experience' => 'nullable|string|max:2000',
            'current_skills' => 'nullable|string|max:2000',
            'challenges' => 'nullable|string|max:2000',
            'success_metrics' => 'nullable|string|max:1000',
            
            // Terms and Conditions
            'accept_terms' => 'required|accepted',
            'accept_data_processing' => 'required|accepted',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'training_title.required' => 'Le titre de la formation est requis.',
            'training_description.required' => 'La description de la formation est requise.',
            'training_type.required' => 'Le type de formation est requis.',
            'target_audience.required' => 'Le public cible est requis.',
            'participant_count.required' => 'Le nombre de participants est requis.',
            'participant_count.min' => 'Le nombre minimum de participants est de 1.',
            'participant_count.max' => 'Le nombre maximum de participants est de 500.',
            'duration_hours.required' => 'La durée de la formation est requise.',
            'duration_hours.min' => 'La durée minimum est de 1 heure.',
            'duration_hours.max' => 'La durée maximum est de 200 heures.',
            'budget_available.min' => 'Le budget minimum est de 0 MAD.',
            'budget_available.max' => 'Le budget maximum est de 999,999,999 MAD.',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'training_title' => 'titre de la formation',
            'training_description' => 'description de la formation',
            'training_type' => 'type de formation',
            'target_audience' => 'public cible',
            'participant_count' => 'nombre de participants',
            'duration_hours' => 'durée de la formation',
            'preferred_location' => 'lieu préféré',
            'preferred_schedule' => 'horaire préféré',
            'budget_available' => 'budget disponible',
            'budget_currency' => 'devise',
        ];
    }
}
