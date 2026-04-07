<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\FormSubmissionController;
use App\Http\Requests\InvestmentSubmissionRequest;
use App\Http\Requests\ProjectCarrierRequest;
use App\Http\Requests\IdeaCarrierRequest;
use App\Http\Requests\AutoEntrepreneurSubmissionRequest;
use App\Http\Requests\INDHSubmissionRequest;
use App\Http\Requests\TrainingSubmissionRequest;

$username = 'mehdi';
$password = 'aloalo';

// Login as mehdi
$user = User::where('username', $username)->first();
if (!$user) {
    echo "User {$username} not found. Please create the user first.\n";
    exit(1);
}

Auth::login($user);
echo "Logged in as {$username}\n";

$controller = new FormSubmissionController();

// Helper function to create a request with data
function createRequest($class, $data) {
    $request = new $class();
    $request->merge($data);
    return $request;
}

// 1. Investment Form
echo "\n=== Submitting Investment Form ===\n";
try {
    $investmentData = [
        'first_name' => 'Mehdi',
        'last_name' => 'Alami',
        'email' => 'mehdi.alami@example.com',
        'phone' => '+212612345678',
        'date_of_birth' => '1990-05-15',
        'nationality' => 'Moroccan',
        'address' => '123 Test Street',
        'city' => 'Casablanca',
        'region' => 'Casablanca-Settat',
        'postal_code' => '20000',
        'project_name' => 'Smart Agriculture IoT Platform',
        'project_description' => 'Plateforme IoT pour l\'agriculture intelligente permettant aux agriculteurs de surveiller leurs cultures en temps réel, optimiser l\'irrigation et améliorer les rendements grâce à des capteurs connectés et des analyses de données.',
        'investment_amount' => 500000,
        'currency' => 'MAD',
        'investment_type' => 'equity',
        'sector' => 'Agriculture',
        'investment_purpose' => 'Financement pour l\'achat d\'équipements IoT, développement de la plateforme et formation des agriculteurs',
        'business_stage' => 'startup',
        'target_market' => 'Agriculteurs marocains, coopératives agricoles',
        'motivation' => 'Améliorer la productivité agricole au Maroc grâce à la technologie',
        'accept_terms' => true,
        'accept_data_processing' => true,
    ];
    $request = createRequest(InvestmentSubmissionRequest::class, $investmentData);
    $result = $controller->submitInvestment($request);
    echo "Investment submission created successfully\n";
} catch (\Exception $e) {
    echo "Error submitting investment form: " . $e->getMessage() . "\n";
}

// 2. Project Carrier Form
echo "\n=== Submitting Project Carrier Form ===\n";
try {
    $projectCarrierData = [
        'first_name' => 'Mehdi',
        'last_name' => 'Alami',
        'email' => 'mehdi.alami@example.com',
        'phone' => '+212612345678',
        'date_of_birth' => '1990-05-15',
        'nationality' => 'Moroccan',
        'address' => '123 Test Street',
        'city' => 'Casablanca',
        'region' => 'Casablanca-Settat',
        'postal_code' => '20000',
        'project_name' => 'E-Commerce Platform for Local Artisans',
        'project_description' => 'Plateforme e-commerce permettant aux artisans marocains de vendre leurs produits en ligne et d\'accéder à de nouveaux marchés.',
        'sector' => 'Commerce',
        'development_stage' => 'prototype',
        'project_type' => 'startup',
        'target_market' => 'Artisans marocains, consommateurs locaux et internationaux',
        'team_size' => 5,
        'team_skills' => 'Développement web, marketing digital, gestion de projet',
        'funding_required' => 300000,
        'funding_currency' => 'MAD',
        'funding_purpose' => 'Développement de la plateforme, marketing et infrastructure',
        'location_region' => 'Casablanca-Settat',
        'location_city' => 'Casablanca',
        'motivation' => 'Soutenir les artisans locaux et préserver le patrimoine culturel marocain',
        'accept_terms' => true,
        'accept_data_processing' => true,
    ];
    $request = createRequest(ProjectCarrierRequest::class, $projectCarrierData);
    $result = $controller->submitProjectCarrier($request);
    echo "Project carrier submission created successfully\n";
} catch (\Exception $e) {
    echo "Error submitting project carrier form: " . $e->getMessage() . "\n";
}

// 3. Idea Carrier Form
echo "\n=== Submitting Idea Carrier Form ===\n";
try {
    $ideaCarrierData = [
        'first_name' => 'Mehdi',
        'last_name' => 'Alami',
        'email' => 'mehdi.alami@example.com',
        'phone' => '+212612345678',
        'date_of_birth' => '1990-05-15',
        'nationality' => 'Moroccan',
        'address' => '123 Test Street',
        'city' => 'Casablanca',
        'region' => 'Casablanca-Settat',
        'postal_code' => '20000',
        'idea_title' => 'Mobile App for Waste Management',
        'idea_description' => 'Application mobile permettant aux citoyens de signaler les déchets et de suivre le processus de collecte et de recyclage.',
        'sector' => 'Environnement',
        'development_level' => 'concept',
        'support_needed' => 'Financement, accompagnement technique, partenariats avec les municipalités',
        'budget_estimate' => 200000,
        'budget_currency' => 'MAD',
        'location_region' => 'Casablanca-Settat',
        'location_city' => 'Casablanca',
        'motivation' => 'Améliorer la gestion des déchets au Maroc et sensibiliser la population',
        'accept_terms' => true,
        'accept_data_processing' => true,
    ];
    $request = createRequest(IdeaCarrierRequest::class, $ideaCarrierData);
    $result = $controller->submitIdeaCarrier($request);
    echo "Idea carrier submission created successfully\n";
} catch (\Exception $e) {
    echo "Error submitting idea carrier form: " . $e->getMessage() . "\n";
}

// 4. Auto Entrepreneur Form
echo "\n=== Submitting Auto Entrepreneur Form ===\n";
try {
    $autoEntrepreneurData = [
        'first_name' => 'Mehdi',
        'last_name' => 'Alami',
        'email' => 'mehdi.alami@example.com',
        'phone' => '+212612345678',
        'date_of_birth' => '1990-05-15',
        'nationality' => 'Moroccan',
        'address' => '123 Test Street',
        'city' => 'Casablanca',
        'region' => 'Casablanca-Settat',
        'postal_code' => '20000',
        'business_name' => 'Digital Marketing Agency',
        'business_description' => 'Agence de marketing digital offrant des services de gestion de réseaux sociaux, création de contenu et publicité en ligne pour les PME.',
        'business_sector' => 'Services',
        'business_type' => 'service',
        'start_date' => date('Y-m-d', strtotime('+1 month')),
        'expected_monthly_revenue' => 15000,
        'business_address' => '123 Test Street',
        'business_city' => 'Casablanca',
        'business_region' => 'Casablanca-Settat',
        'has_legal_status' => false,
        'initial_investment' => 50000,
        'funding_source' => 'personal_savings',
        'monthly_expenses' => 5000,
        'has_bank_account' => true,
        'target_market' => 'PME marocaines, startups, entrepreneurs',
        'motivation' => 'Aider les PME marocaines à développer leur présence en ligne',
        'accept_terms' => true,
        'accept_data_processing' => true,
    ];
    $request = createRequest(AutoEntrepreneurSubmissionRequest::class, $autoEntrepreneurData);
    $result = $controller->submitAutoEntrepreneur($request);
    echo "Auto entrepreneur submission created successfully\n";
} catch (\Exception $e) {
    echo "Error submitting auto entrepreneur form: " . $e->getMessage() . "\n";
}

// 5. INDH Form
echo "\n=== Submitting INDH Form ===\n";
try {
    $indhData = [
        'first_name' => 'Mehdi',
        'last_name' => 'Alami',
        'email' => 'mehdi.alami@example.com',
        'phone' => '+212612345678',
        'date_of_birth' => '1990-05-15',
        'nationality' => 'Moroccan',
        'address' => '123 Test Street',
        'city' => 'Casablanca',
        'region' => 'Casablanca-Settat',
        'postal_code' => '20000',
        'project_title' => 'Centre de Formation pour Jeunes',
        'project_description' => 'Centre de formation offrant des cours de compétences numériques, langues et entrepreneuriat aux jeunes de la région.',
        'project_type' => 'social',
        'project_category' => 'youth_empowerment',
        'community_impact' => 'Formation de 200 jeunes par an, création d\'emplois, amélioration des compétences, réduction du chômage des jeunes dans la région.',
        'target_beneficiaries' => 200,
        'project_goals' => 'Former 200 jeunes par an en compétences numériques, langues et entrepreneuriat',
        'expected_outcomes' => 'Amélioration des compétences, création d\'emplois, réduction du chômage',
        'funding_required' => 400000,
        'funding_currency' => 'MAD',
        'project_duration_months' => 12,
        'project_scope' => 'local',
        'community_involvement' => 'Implication active de la communauté locale, partenariats avec les associations et les entreprises',
        'location_region' => 'Casablanca-Settat',
        'location_city' => 'Casablanca',
        'motivation' => 'Réduire le chômage des jeunes et améliorer leurs compétences',
        'accept_terms' => true,
        'accept_data_processing' => true,
    ];
    $request = createRequest(INDHSubmissionRequest::class, $indhData);
    $result = $controller->submitINDH($request);
    echo "INDH submission created successfully\n";
} catch (\Exception $e) {
    echo "Error submitting INDH form: " . $e->getMessage() . "\n";
}

// 6. Training Form
echo "\n=== Submitting Training Form ===\n";
try {
    $trainingData = [
        'first_name' => 'Mehdi',
        'last_name' => 'Alami',
        'email' => 'mehdi.alami@example.com',
        'phone' => '+212612345678',
        'date_of_birth' => '1990-05-15',
        'nationality' => 'Moroccan',
        'address' => '123 Test Street',
        'city' => 'Casablanca',
        'region' => 'Casablanca-Settat',
        'postal_code' => '20000',
        'training_title' => 'Formation en Marketing Digital',
        'training_description' => 'Formation complète en marketing digital incluant SEO, publicité en ligne, gestion de réseaux sociaux et analytics.',
        'training_type' => 'business',
        'training_category' => 'professional_development',
        'target_audience' => 'Professionnels du marketing, entrepreneurs, étudiants en commerce',
        'participant_count' => 25,
        'duration_hours' => 40,
        'training_format' => 'hybrid',
        'language_preference' => 'french',
        'preferred_location' => 'Casablanca',
        'preferred_schedule' => 'Soirées et weekends',
        'budget_available' => 50000,
        'budget_currency' => 'MAD',
        'learning_objectives' => 'Maîtriser les outils de marketing digital, créer des campagnes publicitaires efficaces, analyser les performances',
        'expected_outcomes' => 'Certification en marketing digital, amélioration des compétences professionnelles',
        'motivation' => 'Développer mes compétences en marketing digital pour progresser dans ma carrière',
        'accept_terms' => true,
        'accept_data_processing' => true,
    ];
    $request = createRequest(TrainingSubmissionRequest::class, $trainingData);
    $result = $controller->submitTraining($request);
    echo "Training submission created successfully\n";
} catch (\Exception $e) {
    echo "Error submitting training form: " . $e->getMessage() . "\n";
}

echo "\n=== All forms submitted ===\n";
echo "Please check the admin and sectoral admin dashboards to verify submissions appear correctly.\n";

