<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectCarrierRequest extends FormRequest
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
            
            // Project Information
            'project_name' => 'required|string|max:255',
            'project_description' => 'required|string|max:5000',
            'sector' => 'required|string|max:255',
            'development_stage' => 'required|string|in:idea,prototype,mvp,scaling,established',
            'project_type' => 'required|string|in:startup,expansion,innovation,research,development,autre',
            'target_market' => 'required|string|max:1000',
            'competitive_advantage' => 'nullable|string|max:2000',
            'market_size' => 'nullable|string|max:1000',
            
            // Team & Resources
            'team_size' => 'required|integer|min:1|max:100',
            'team_skills' => 'nullable|string|max:1000',
            'previous_experience' => 'nullable|string|max:2000',
            'partnerships' => 'nullable|string|max:1000',
            
            // Financial Information
            'funding_required' => 'required|numeric|min:1000|max:999999999',
            'funding_currency' => 'required|string|in:MAD,EUR,USD',
            'funding_purpose' => 'required|string|max:2000',
            'funding_source' => 'nullable|string|in:personal_savings,family_loan,bank_loan,investor,government_support,autre',
            'revenue_model' => 'nullable|string|max:1000',
            'expected_roi' => 'nullable|string|max:1000',
            
            // Location
            'location_region' => 'required|string|max:255',
            'location_city' => 'required|string|max:255',
            
            // Documents
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'business_plan' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'financial_projections' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'market_analysis' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'technical_documentation' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            
            // Additional Information
            'motivation' => 'required|string|max:2000',
            'challenges' => 'nullable|string|max:2000',
            'timeline' => 'nullable|string|max:1000',
            'success_metrics' => 'nullable|string|max:1000',
            'risk_assessment' => 'nullable|string|max:2000',
            
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
            'project_name.required' => 'Le nom du projet est obligatoire.',
            'project_description.required' => 'La description du projet est obligatoire.',
            'sector.required' => 'Le secteur d\'activité est obligatoire.',
            'development_stage.required' => 'Le stade de développement est obligatoire.',
            'development_stage.in' => 'Le stade de développement sélectionné n\'est pas valide.',
            'project_type.required' => 'Le type de projet est obligatoire.',
            'project_type.in' => 'Le type de projet sélectionné n\'est pas valide.',
            'target_market.required' => 'Le marché cible est obligatoire.',
            'team_size.required' => 'La taille de l\'équipe est obligatoire.',
            'team_size.min' => 'La taille minimum de l\'équipe est de 1 personne.',
            'team_size.max' => 'La taille maximum de l\'équipe est de 100 personnes.',
            'funding_required.required' => 'Le montant de financement requis est obligatoire.',
            'funding_required.min' => 'Le montant minimum est de 1,000 MAD.',
            'funding_required.max' => 'Le montant maximum est de 999,999,999 MAD.',
            'funding_currency.required' => 'La devise est obligatoire.',
            'funding_currency.in' => 'La devise sélectionnée n\'est pas valide.',
            'funding_purpose.required' => 'L\'objectif du financement est obligatoire.',
            'funding_source.in' => 'La source de financement sélectionnée n\'est pas valide.',
            'location_region.required' => 'La région est obligatoire.',
            'location_city.required' => 'La ville est obligatoire.',
            'motivation.required' => 'La motivation est obligatoire.',
            'cv.file' => 'Le CV doit être un fichier.',
            'cv.mimes' => 'Le CV doit être un fichier PDF, DOC ou DOCX.',
            'cv.max' => 'Le CV ne doit pas dépasser 10MB.',
            'business_plan.file' => 'Le plan d\'affaires doit être un fichier.',
            'business_plan.mimes' => 'Le plan d\'affaires doit être un fichier PDF, DOC ou DOCX.',
            'business_plan.max' => 'Le plan d\'affaires ne doit pas dépasser 10MB.',
            'financial_projections.file' => 'Les projections financières doivent être un fichier.',
            'financial_projections.mimes' => 'Les projections financières doivent être un fichier PDF, DOC, DOCX, XLS ou XLSX.',
            'financial_projections.max' => 'Les projections financières ne doivent pas dépasser 10MB.',
            'market_analysis.file' => 'L\'analyse de marché doit être un fichier.',
            'market_analysis.mimes' => 'L\'analyse de marché doit être un fichier PDF, DOC ou DOCX.',
            'market_analysis.max' => 'L\'analyse de marché ne doit pas dépasser 10MB.',
            'technical_documentation.file' => 'La documentation technique doit être un fichier.',
            'technical_documentation.mimes' => 'La documentation technique doit être un fichier PDF, DOC ou DOCX.',
            'technical_documentation.max' => 'La documentation technique ne doit pas dépasser 10MB.',
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
            'project_name' => 'nom du projet',
            'project_description' => 'description du projet',
            'sector' => 'secteur d\'activité',
            'development_stage' => 'stade de développement',
            'project_type' => 'type de projet',
            'target_market' => 'marché cible',
            'competitive_advantage' => 'avantage concurrentiel',
            'market_size' => 'taille du marché',
            'team_size' => 'taille de l\'équipe',
            'team_skills' => 'compétences de l\'équipe',
            'previous_experience' => 'expérience précédente',
            'partnerships' => 'partenariats',
            'funding_required' => 'financement requis',
            'funding_currency' => 'devise',
            'funding_purpose' => 'objectif du financement',
            'funding_source' => 'source de financement',
            'revenue_model' => 'modèle de revenus',
            'expected_roi' => 'ROI attendu',
            'location_region' => 'région',
            'location_city' => 'ville',
            'cv' => 'CV',
            'business_plan' => 'plan d\'affaires',
            'financial_projections' => 'projections financières',
            'market_analysis' => 'analyse de marché',
            'technical_documentation' => 'documentation technique',
            'motivation' => 'motivation',
            'challenges' => 'défis',
            'timeline' => 'calendrier',
            'success_metrics' => 'métriques de succès',
            'risk_assessment' => 'évaluation des risques',
            'accept_terms' => 'conditions d\'utilisation',
            'accept_data_processing' => 'traitement des données',
        ];
    }
}
