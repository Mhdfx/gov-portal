<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IdeaCarrierRequest extends FormRequest
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
            
            // Idea Information
            'idea_title' => 'required|string|max:255',
            'idea_description' => 'required|string|max:5000',
            'sector' => 'required|string|max:255',
            'development_level' => 'required|string|in:concept,research,prototype,testing,ready_for_development',
            'innovation_type' => 'required|string|in:technological,service,business_model,social,environmental,autre',
            'target_market' => 'required|string|max:1000',
            'competitive_advantage' => 'nullable|string|max:2000',
            'market_potential' => 'nullable|string|max:2000',
            
            // Support & Resources
            'support_needed' => 'nullable|string|max:2000',
            'budget_estimate' => 'nullable|numeric|min:100|max:999999999',
            'budget_currency' => 'required_with:budget_estimate|string|in:MAD,EUR,USD',
            'funding_source' => 'nullable|string|in:personal_savings,family_loan,bank_loan,investor,government_support,autre',
            'team_size' => 'nullable|integer|min:1|max:50',
            'team_skills' => 'nullable|string|max:1000',
            
            // Location
            'location_region' => 'required|string|max:255',
            'location_city' => 'required|string|max:255',
            
            // Documents
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'idea_document' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'market_research' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'prototype_images' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
            
            // Additional Information
            'previous_experience' => 'nullable|string|max:2000',
            'motivation' => 'required|string|max:2000',
            'challenges' => 'nullable|string|max:2000',
            'timeline' => 'nullable|string|max:1000',
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
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom de famille est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'phone.required' => 'Le numéro de téléphone est obligatoire.',
            'date_of_birth.required' => 'La date de naissance est obligatoire.',
            'date_of_birth.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'idea_title.required' => 'Le titre de l\'idée est obligatoire.',
            'idea_description.required' => 'La description de l\'idée est obligatoire.',
            'sector.required' => 'Le secteur d\'activité est obligatoire.',
            'development_level.required' => 'Le niveau de développement est obligatoire.',
            'development_level.in' => 'Le niveau de développement sélectionné n\'est pas valide.',
            'innovation_type.required' => 'Le type d\'innovation est obligatoire.',
            'innovation_type.in' => 'Le type d\'innovation sélectionné n\'est pas valide.',
            'target_market.required' => 'Le marché cible est obligatoire.',
            'budget_estimate.min' => 'Le budget minimum est de 100 MAD.',
            'budget_estimate.max' => 'Le budget maximum est de 999,999,999 MAD.',
            'budget_currency.required_with' => 'La devise est obligatoire si un budget est spécifié.',
            'budget_currency.in' => 'La devise sélectionnée n\'est pas valide.',
            'funding_source.in' => 'La source de financement sélectionnée n\'est pas valide.',
            'team_size.min' => 'La taille de l\'équipe doit être d\'au moins 1 personne.',
            'team_size.max' => 'La taille de l\'équipe ne peut pas dépasser 50 personnes.',
            'location_region.required' => 'La région est obligatoire.',
            'location_city.required' => 'La ville est obligatoire.',
            'motivation.required' => 'La motivation est obligatoire.',
            'cv.file' => 'Le CV doit être un fichier.',
            'cv.mimes' => 'Le CV doit être un fichier PDF, DOC ou DOCX.',
            'cv.max' => 'Le CV ne doit pas dépasser 10MB.',
            'idea_document.file' => 'Le document d\'idée doit être un fichier.',
            'idea_document.mimes' => 'Le document d\'idée doit être un fichier PDF, DOC ou DOCX.',
            'idea_document.max' => 'Le document d\'idée ne doit pas dépasser 10MB.',
            'market_research.file' => 'L\'étude de marché doit être un fichier.',
            'market_research.mimes' => 'L\'étude de marché doit être un fichier PDF, DOC ou DOCX.',
            'market_research.max' => 'L\'étude de marché ne doit pas dépasser 10MB.',
            'prototype_images.file' => 'Les images du prototype doivent être des fichiers.',
            'prototype_images.mimes' => 'Les images du prototype doivent être des fichiers JPG, JPEG ou PNG.',
            'prototype_images.max' => 'Les images du prototype ne doivent pas dépasser 10MB.',
            'accept_terms.required' => 'Vous devez accepter les conditions d\'utilisation.',
            'accept_terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
            'accept_data_processing.required' => 'Vous devez accepter le traitement des données.',
            'accept_data_processing.accepted' => 'Vous devez accepter le traitement des données.',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'prénom',
            'last_name' => 'nom de famille',
            'email' => 'adresse email',
            'phone' => 'numéro de téléphone',
            'date_of_birth' => 'date de naissance',
            'nationality' => 'nationalité',
            'address' => 'adresse',
            'city' => 'ville',
            'region' => 'région',
            'postal_code' => 'code postal',
            'idea_title' => 'titre de l\'idée',
            'idea_description' => 'description de l\'idée',
            'sector' => 'secteur d\'activité',
            'development_level' => 'niveau de développement',
            'innovation_type' => 'type d\'innovation',
            'target_market' => 'marché cible',
            'competitive_advantage' => 'avantage concurrentiel',
            'market_potential' => 'potentiel du marché',
            'support_needed' => 'support nécessaire',
            'budget_estimate' => 'estimation du budget',
            'budget_currency' => 'devise',
            'funding_source' => 'source de financement',
            'team_size' => 'taille de l\'équipe',
            'team_skills' => 'compétences de l\'équipe',
            'location_region' => 'région',
            'location_city' => 'ville',
            'cv' => 'CV',
            'idea_document' => 'document d\'idée',
            'market_research' => 'étude de marché',
            'prototype_images' => 'images du prototype',
            'previous_experience' => 'expérience précédente',
            'motivation' => 'motivation',
            'challenges' => 'défis',
            'timeline' => 'calendrier',
            'success_metrics' => 'métriques de succès',
            'accept_terms' => 'conditions d\'utilisation',
            'accept_data_processing' => 'traitement des données',
        ];
    }
}
