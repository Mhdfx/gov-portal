<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class INDHSubmissionRequest extends FormRequest
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
            'project_title' => 'required|string|max:255',
            'project_description' => 'required|string|max:5000',
            'project_type' => 'required|string|in:social,economic,environmental,cultural,educational,health,infrastructure,agriculture,autre',
            'project_category' => 'required|string|in:community_development,youth_empowerment,women_empowerment,rural_development,urban_development,autre',
            'community_impact' => 'required|string|max:3000',
            'target_beneficiaries' => 'required|integer|min:1|max:10000',
            'beneficiary_groups' => 'nullable|string|max:1000',
            'project_goals' => 'required|string|max:2000',
            'expected_outcomes' => 'required|string|max:2000',
            
            // Financial Information
            'funding_required' => 'required|numeric|min:1000|max:999999999',
            'funding_currency' => 'required|string|in:MAD,EUR,USD',
            'funding_breakdown' => 'nullable|string|max:2000',
            'co_funding_sources' => 'nullable|string|max:1000',
            'sustainability_plan' => 'nullable|string|max:2000',
            
            // Timeline & Implementation
            'project_duration_months' => 'required|integer|min:1|max:120',
            'start_date' => 'nullable|date|after:today',
            'implementation_phases' => 'nullable|string|max:2000',
            'key_milestones' => 'nullable|string|max:2000',
            
            // Location & Scope
            'location_region' => 'required|string|max:255',
            'location_city' => 'required|string|max:255',
            'project_scope' => 'required|string|in:local,regional,national',
            'geographic_coverage' => 'nullable|string|max:1000',
            
            // Partnership & Collaboration
            'partner_organizations' => 'nullable|string|max:2000',
            'government_support' => 'nullable|string|max:1000',
            'community_involvement' => 'required|string|max:2000',
            'stakeholder_engagement' => 'nullable|string|max:2000',
            
            // Documents
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'project_proposal' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'budget_detailed' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'community_letters' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'partnership_agreements' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            
            // Additional Information
            'motivation' => 'required|string|max:2000',
            'previous_experience' => 'nullable|string|max:2000',
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
            'project_title.required' => 'Le titre du projet est obligatoire.',
            'project_description.required' => 'La description du projet est obligatoire.',
            'project_type.required' => 'Le type de projet est obligatoire.',
            'project_type.in' => 'Le type de projet sélectionné n\'est pas valide.',
            'project_category.required' => 'La catégorie du projet est obligatoire.',
            'project_category.in' => 'La catégorie du projet sélectionnée n\'est pas valide.',
            'community_impact.required' => 'L\'impact communautaire est obligatoire.',
            'target_beneficiaries.required' => 'Le nombre de bénéficiaires cibles est obligatoire.',
            'target_beneficiaries.min' => 'Le nombre minimum de bénéficiaires est de 1.',
            'target_beneficiaries.max' => 'Le nombre maximum de bénéficiaires est de 10,000.',
            'project_goals.required' => 'Les objectifs du projet sont obligatoires.',
            'expected_outcomes.required' => 'Les résultats attendus sont obligatoires.',
            'funding_required.required' => 'Le montant de financement requis est obligatoire.',
            'funding_required.min' => 'Le montant minimum est de 1,000 MAD.',
            'funding_required.max' => 'Le montant maximum est de 999,999,999 MAD.',
            'funding_currency.required' => 'La devise est obligatoire.',
            'funding_currency.in' => 'La devise sélectionnée n\'est pas valide.',
            'project_duration_months.required' => 'La durée du projet est obligatoire.',
            'project_duration_months.min' => 'La durée minimum est de 1 mois.',
            'project_duration_months.max' => 'La durée maximum est de 120 mois.',
            'start_date.after' => 'La date de début doit être postérieure à aujourd\'hui.',
            'location_region.required' => 'La région est obligatoire.',
            'location_city.required' => 'La ville est obligatoire.',
            'project_scope.required' => 'La portée du projet est obligatoire.',
            'project_scope.in' => 'La portée du projet sélectionnée n\'est pas valide.',
            'community_involvement.required' => 'L\'implication communautaire est obligatoire.',
            'motivation.required' => 'La motivation est obligatoire.',
            'cv.file' => 'Le CV doit être un fichier.',
            'cv.mimes' => 'Le CV doit être un fichier PDF, DOC ou DOCX.',
            'cv.max' => 'Le CV ne doit pas dépasser 10MB.',
            'project_proposal.file' => 'La proposition de projet doit être un fichier.',
            'project_proposal.mimes' => 'La proposition de projet doit être un fichier PDF, DOC ou DOCX.',
            'project_proposal.max' => 'La proposition de projet ne doit pas dépasser 10MB.',
            'budget_detailed.file' => 'Le budget détaillé doit être un fichier.',
            'budget_detailed.mimes' => 'Le budget détaillé doit être un fichier PDF, DOC, DOCX, XLS ou XLSX.',
            'budget_detailed.max' => 'Le budget détaillé ne doit pas dépasser 10MB.',
            'community_letters.file' => 'Les lettres communautaires doivent être un fichier.',
            'community_letters.mimes' => 'Les lettres communautaires doivent être un fichier PDF, DOC ou DOCX.',
            'community_letters.max' => 'Les lettres communautaires ne doivent pas dépasser 10MB.',
            'partnership_agreements.file' => 'Les accords de partenariat doivent être un fichier.',
            'partnership_agreements.mimes' => 'Les accords de partenariat doivent être un fichier PDF, DOC ou DOCX.',
            'partnership_agreements.max' => 'Les accords de partenariat ne doivent pas dépasser 10MB.',
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
            'project_title' => 'titre du projet',
            'project_description' => 'description du projet',
            'project_type' => 'type de projet',
            'project_category' => 'catégorie du projet',
            'community_impact' => 'impact communautaire',
            'target_beneficiaries' => 'bénéficiaires cibles',
            'beneficiary_groups' => 'groupes de bénéficiaires',
            'project_goals' => 'objectifs du projet',
            'expected_outcomes' => 'résultats attendus',
            'funding_required' => 'financement requis',
            'funding_currency' => 'devise',
            'funding_breakdown' => 'répartition du financement',
            'co_funding_sources' => 'sources de cofinancement',
            'sustainability_plan' => 'plan de durabilité',
            'project_duration_months' => 'durée du projet',
            'start_date' => 'date de début',
            'implementation_phases' => 'phases d\'implémentation',
            'key_milestones' => 'jalons clés',
            'location_region' => 'région',
            'location_city' => 'ville',
            'project_scope' => 'portée du projet',
            'geographic_coverage' => 'couverture géographique',
            'partner_organizations' => 'organisations partenaires',
            'government_support' => 'soutien gouvernemental',
            'community_involvement' => 'implication communautaire',
            'stakeholder_engagement' => 'engagement des parties prenantes',
            'cv' => 'CV',
            'project_proposal' => 'proposition de projet',
            'budget_detailed' => 'budget détaillé',
            'community_letters' => 'lettres communautaires',
            'partnership_agreements' => 'accords de partenariat',
            'motivation' => 'motivation',
            'previous_experience' => 'expérience précédente',
            'challenges' => 'défis',
            'success_metrics' => 'métriques de succès',
            'risk_assessment' => 'évaluation des risques',
            'accept_terms' => 'conditions d\'utilisation',
            'accept_data_processing' => 'traitement des données',
        ];
    }
}
