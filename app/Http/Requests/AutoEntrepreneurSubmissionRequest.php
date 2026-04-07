<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AutoEntrepreneurSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            
            // Auto-Entrepreneur Information
            'business_name' => 'required|string|max:255',
            'business_description' => 'required|string|max:2000',
            'business_type' => 'required|string|in:commerce,service,artisanat,profession_liberale,autre',
            'business_sector' => 'required|string|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'expected_monthly_revenue' => 'required|numeric|min:0|max:999999.99',
            'business_address' => 'required|string|max:500',
            'business_city' => 'required|string|max:100',
            'business_region' => 'required|string|max:100',
            
            // Legal Information
            'has_legal_status' => 'required|boolean',
            'legal_status_type' => 'required_if:has_legal_status,true|nullable|string|in:auto_entrepreneur,entreprise_individuale,autre',
            'registration_number' => 'nullable|string|max:100',
            'tax_number' => 'nullable|string|max:100',
            
            // Financial Information
            'initial_investment' => 'required|numeric|min:0|max:999999.99',
            'funding_source' => 'required|string|in:personal_savings,family_loan,bank_loan,investor,government_support,autre',
            'monthly_expenses' => 'required|numeric|min:0|max:999999.99',
            'has_bank_account' => 'required|boolean',
            'bank_name' => 'nullable|string|max:255',
            
            // Documents
            'identity_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
            'business_plan' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'financial_projections' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            
            // Additional Information
            'previous_experience' => 'nullable|string|max:2000',
            'skills' => 'nullable|string|max:1000',
            'languages' => 'nullable|string|max:500',
            'motivation' => 'required|string|max:2000',
            'challenges' => 'nullable|string|max:2000',
            'support_needed' => 'nullable|string|max:2000',
            
            // Marketing & Sales
            'target_market' => 'required|string|max:1000',
            'marketing_strategy' => 'nullable|string|max:2000',
            'competitive_advantage' => 'nullable|string|max:1000',
            
            // Terms and Conditions
            'accept_terms' => 'required|accepted',
            'accept_data_processing' => 'required|accepted',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
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
            'business_name.required' => 'Le nom de l\'entreprise est obligatoire.',
            'business_description.required' => 'La description de l\'entreprise est obligatoire.',
            'business_type.required' => 'Le type d\'entreprise est obligatoire.',
            'business_type.in' => 'Le type d\'entreprise sélectionné n\'est pas valide.',
            'start_date.required' => 'La date de début d\'activité est obligatoire.',
            'start_date.after_or_equal' => 'La date de début doit être aujourd\'hui ou dans le futur.',
            'expected_monthly_revenue.required' => 'Le chiffre d\'affaires mensuel prévu est obligatoire.',
            'expected_monthly_revenue.numeric' => 'Le chiffre d\'affaires doit être un nombre.',
            'initial_investment.required' => 'L\'investissement initial est obligatoire.',
            'initial_investment.numeric' => 'L\'investissement initial doit être un nombre.',
            'funding_source.required' => 'La source de financement est obligatoire.',
            'funding_source.in' => 'La source de financement sélectionnée n\'est pas valide.',
            'monthly_expenses.required' => 'Les dépenses mensuelles sont obligatoires.',
            'monthly_expenses.numeric' => 'Les dépenses mensuelles doivent être un nombre.',
            'target_market.required' => 'Le marché cible est obligatoire.',
            'motivation.required' => 'La motivation est obligatoire.',
            'identity_document.required' => 'Le document d\'identité est obligatoire.',
            'identity_document.file' => 'Le document d\'identité doit être un fichier.',
            'identity_document.mimes' => 'Le document d\'identité doit être un fichier PDF, JPG, JPEG ou PNG.',
            'identity_document.max' => 'Le document d\'identité ne doit pas dépasser 10MB.',
            'accept_terms.required' => 'Vous devez accepter les conditions d\'utilisation.',
            'accept_terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
            'accept_data_processing.required' => 'Vous devez accepter le traitement des données.',
            'accept_data_processing.accepted' => 'Vous devez accepter le traitement des données.',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
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
            'business_name' => 'nom de l\'entreprise',
            'business_description' => 'description de l\'entreprise',
            'business_type' => 'type d\'entreprise',
            'business_sector' => 'secteur d\'activité',
            'start_date' => 'date de début',
            'expected_monthly_revenue' => 'chiffre d\'affaires mensuel prévu',
            'business_address' => 'adresse de l\'entreprise',
            'business_city' => 'ville de l\'entreprise',
            'business_region' => 'région de l\'entreprise',
            'has_legal_status' => 'statut légal',
            'legal_status_type' => 'type de statut légal',
            'registration_number' => 'numéro d\'enregistrement',
            'tax_number' => 'numéro fiscal',
            'initial_investment' => 'investissement initial',
            'funding_source' => 'source de financement',
            'monthly_expenses' => 'dépenses mensuelles',
            'has_bank_account' => 'compte bancaire',
            'bank_name' => 'nom de la banque',
            'identity_document' => 'document d\'identité',
            'business_plan' => 'plan d\'affaires',
            'financial_projections' => 'projections financières',
            'cv' => 'CV',
            'previous_experience' => 'expérience précédente',
            'skills' => 'compétences',
            'languages' => 'langues',
            'motivation' => 'motivation',
            'challenges' => 'défis',
            'support_needed' => 'aide nécessaire',
            'target_market' => 'marché cible',
            'marketing_strategy' => 'stratégie marketing',
            'competitive_advantage' => 'avantage concurrentiel',
            'accept_terms' => 'conditions d\'utilisation',
            'accept_data_processing' => 'traitement des données',
        ];
    }
}






























