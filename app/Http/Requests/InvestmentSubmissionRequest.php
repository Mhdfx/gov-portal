<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvestmentSubmissionRequest extends FormRequest
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
            
            // Investment Information
            'project_name' => 'required|string|max:255',
            'project_description' => 'required|string|max:5000',
            'investment_amount' => 'required|numeric|min:1000|max:999999999',
            'currency' => 'required|string|in:MAD,EUR,USD',
            'investment_type' => 'required|string|in:equity,loan,grant,partnership,venture_capital,angel_investment,autre',
            'sector' => 'required|string|max:255',
            'investment_purpose' => 'required|string|max:2000',
            'expected_return' => 'nullable|string|max:1000',
            'investment_timeline' => 'nullable|string|max:1000',
            
            // Business Information
            'business_stage' => 'required|string|in:startup,growth,expansion,mature,autre',
            'revenue_model' => 'nullable|string|max:1000',
            'target_market' => 'required|string|max:1000',
            'competitive_advantage' => 'nullable|string|max:2000',
            'market_size' => 'nullable|string|max:1000',
            
            // Team Information
            'team_size' => 'nullable|integer|min:1|max:100',
            'team_experience' => 'nullable|string|max:2000',
            'key_team_members' => 'nullable|string|max:2000',
            
            // Financial Information
            'current_revenue' => 'nullable|numeric|min:0|max:999999999',
            'projected_revenue' => 'nullable|numeric|min:0|max:999999999',
            'funding_source' => 'nullable|string|in:personal_savings,family_loan,bank_loan,investor,government_support,autre',
            'previous_funding' => 'nullable|string|max:1000',
            
            // Documents
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'business_plan' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'financial_projections' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'market_analysis' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'legal_documents' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            
            // Additional Information
            'motivation' => 'required|string|max:2000',
            'challenges' => 'nullable|string|max:2000',
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
            'investment_amount.required' => 'Le montant de l\'investissement est obligatoire.',
            'investment_amount.min' => 'Le montant minimum est de 1,000 MAD.',
            'investment_amount.max' => 'Le montant maximum est de 999,999,999 MAD.',
            'currency.required' => 'La devise est obligatoire.',
            'currency.in' => 'La devise sélectionnée n\'est pas valide.',
            'investment_type.required' => 'Le type d\'investissement est obligatoire.',
            'investment_type.in' => 'Le type d\'investissement sélectionné n\'est pas valide.',
            'sector.required' => 'Le secteur d\'activité est obligatoire.',
            'investment_purpose.required' => 'L\'objectif de l\'investissement est obligatoire.',
            'business_stage.required' => 'Le stade de l\'entreprise est obligatoire.',
            'business_stage.in' => 'Le stade de l\'entreprise sélectionné n\'est pas valide.',
            'target_market.required' => 'Le marché cible est obligatoire.',
            'team_size.min' => 'La taille de l\'équipe doit être d\'au moins 1 personne.',
            'team_size.max' => 'La taille de l\'équipe ne peut pas dépasser 100 personnes.',
            'current_revenue.min' => 'Le revenu actuel ne peut pas être négatif.',
            'current_revenue.max' => 'Le revenu actuel ne peut pas dépasser 999,999,999 MAD.',
            'projected_revenue.min' => 'Le revenu projeté ne peut pas être négatif.',
            'projected_revenue.max' => 'Le revenu projeté ne peut pas dépasser 999,999,999 MAD.',
            'funding_source.in' => 'La source de financement sélectionnée n\'est pas valide.',
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
            'legal_documents.file' => 'Les documents légaux doivent être un fichier.',
            'legal_documents.mimes' => 'Les documents légaux doivent être un fichier PDF, DOC ou DOCX.',
            'legal_documents.max' => 'Les documents légaux ne doivent pas dépasser 10MB.',
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
            'investment_amount' => 'montant de l\'investissement',
            'currency' => 'devise',
            'investment_type' => 'type d\'investissement',
            'sector' => 'secteur d\'activité',
            'investment_purpose' => 'objectif de l\'investissement',
            'expected_return' => 'retour attendu',
            'investment_timeline' => 'calendrier d\'investissement',
            'business_stage' => 'stade de l\'entreprise',
            'revenue_model' => 'modèle de revenus',
            'target_market' => 'marché cible',
            'competitive_advantage' => 'avantage concurrentiel',
            'market_size' => 'taille du marché',
            'team_size' => 'taille de l\'équipe',
            'team_experience' => 'expérience de l\'équipe',
            'key_team_members' => 'membres clés de l\'équipe',
            'current_revenue' => 'revenu actuel',
            'projected_revenue' => 'revenu projeté',
            'funding_source' => 'source de financement',
            'previous_funding' => 'financement précédent',
            'cv' => 'CV',
            'business_plan' => 'plan d\'affaires',
            'financial_projections' => 'projections financières',
            'market_analysis' => 'analyse de marché',
            'legal_documents' => 'documents légaux',
            'motivation' => 'motivation',
            'challenges' => 'défis',
            'success_metrics' => 'métriques de succès',
            'risk_assessment' => 'évaluation des risques',
            'accept_terms' => 'conditions d\'utilisation',
            'accept_data_processing' => 'traitement des données',
        ];
    }
}
