<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\AutoEntrepreneurSubmission;
use App\Models\PorteurIdeeSubmission;
use App\Models\PorteurProjetSubmission;
use App\Models\InvestmentSubmission;
use App\Models\INDHSubmission;
use App\Models\TrainingSubmission;
use App\Http\Resources\AutoEntrepreneurResource;
use App\Http\Resources\PorteurIdeeResource;
use App\Http\Resources\PorteurProjetResource;
use App\Http\Resources\InvestmentResource;
use App\Http\Resources\INDHResource;
use App\Http\Resources\TrainingResource;

class FormSubmissionController extends Controller
{
    /**
     * Submit auto entrepreneur form.
     */
    public function submitAutoEntrepreneur(Request $request): JsonResponse
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:100',
            'business_description' => 'required|string|max:2000',
            'business_address' => 'required|string|max:500',
            'business_city' => 'required|string|max:100',
            'business_region' => 'required|string|max:100',
            'business_phone' => 'required|string|max:20',
            'business_email' => 'required|email|max:255',
            'business_sector' => 'required|string|max:100',
            'business_activity' => 'required|string|max:200',
            'business_legal_form' => 'required|string|max:100',
            'business_start_date' => 'required|date',
            'business_capital' => 'required|numeric|min:0',
            'business_employees_count' => 'required|integer|min:0',
            'business_turnover' => 'required|numeric|min:0',
            'business_profit' => 'required|numeric|min:0',
            'business_challenges' => 'required|string|max:2000',
            'business_opportunities' => 'required|string|max:2000',
            'business_goals' => 'required|string|max:2000',
            'business_needs' => 'required|string|max:2000',
            'business_support_requested' => 'required|boolean',
            'business_support_type' => 'required|string|max:100',
            'business_support_amount' => 'required|numeric|min:0',
            'business_support_description' => 'required|string|max:2000',
            'business_support_timeline' => 'required|string|max:200',
            'business_support_expected_outcome' => 'required|string|max:2000',
        ]);

        $submission = AutoEntrepreneurSubmission::create([
            'user_id' => $request->user()->id,
            'business_name' => $request->business_name,
            'business_type' => $request->business_type,
            'business_description' => $request->business_description,
            'business_address' => $request->business_address,
            'business_city' => $request->business_city,
            'business_region' => $request->business_region,
            'business_phone' => $request->business_phone,
            'business_email' => $request->business_email,
            'business_sector' => $request->business_sector,
            'business_activity' => $request->business_activity,
            'business_legal_form' => $request->business_legal_form,
            'business_start_date' => $request->business_start_date,
            'business_capital' => $request->business_capital,
            'business_employees_count' => $request->business_employees_count,
            'business_turnover' => $request->business_turnover,
            'business_profit' => $request->business_profit,
            'business_challenges' => $request->business_challenges,
            'business_opportunities' => $request->business_opportunities,
            'business_goals' => $request->business_goals,
            'business_needs' => $request->business_needs,
            'business_support_requested' => $request->business_support_requested,
            'business_support_type' => $request->business_support_type,
            'business_support_amount' => $request->business_support_amount,
            'business_support_description' => $request->business_support_description,
            'business_support_timeline' => $request->business_support_timeline,
            'business_support_expected_outcome' => $request->business_support_expected_outcome,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Auto entrepreneur submission created successfully',
            'data' => new AutoEntrepreneurResource($submission),
        ], 201);
    }

    /**
     * Submit idea carrier form.
     */
    public function submitIdeaCarrier(Request $request): JsonResponse
    {
        $request->validate([
            'idea_title' => 'required|string|max:255',
            'idea_description' => 'required|string|max:2000',
            'idea_sector' => 'required|string|max:100',
            'idea_innovation_level' => 'required|string|max:100',
            'idea_target_market' => 'required|string|max:200',
            'idea_competitive_advantage' => 'required|string|max:2000',
            'idea_business_model' => 'required|string|max:2000',
            'idea_revenue_streams' => 'required|string|max:2000',
            'idea_funding_requirements' => 'required|numeric|min:0',
            'idea_timeline' => 'required|string|max:200',
            'idea_team_size' => 'required|integer|min:1',
            'idea_team_experience' => 'required|string|max:2000',
            'idea_previous_experience' => 'required|boolean',
            'idea_previous_experience_details' => 'required|string|max:2000',
        ]);

        $submission = PorteurIdeeSubmission::create([
            'user_id' => $request->user()->id,
            'idea_title' => $request->idea_title,
            'idea_description' => $request->idea_description,
            'idea_sector' => $request->idea_sector,
            'idea_innovation_level' => $request->idea_innovation_level,
            'idea_target_market' => $request->idea_target_market,
            'idea_competitive_advantage' => $request->idea_competitive_advantage,
            'idea_business_model' => $request->idea_business_model,
            'idea_revenue_streams' => $request->idea_revenue_streams,
            'idea_funding_requirements' => $request->idea_funding_requirements,
            'idea_timeline' => $request->idea_timeline,
            'idea_team_size' => $request->idea_team_size,
            'idea_team_experience' => $request->idea_team_experience,
            'idea_previous_experience' => $request->idea_previous_experience,
            'idea_previous_experience_details' => $request->idea_previous_experience_details,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Idea carrier submission created successfully',
            'data' => new PorteurIdeeResource($submission),
        ], 201);
    }

    /**
     * Submit project carrier form.
     */
    public function submitProjectCarrier(Request $request): JsonResponse
    {
        $request->validate([
            'project_title' => 'required|string|max:255',
            'project_description' => 'required|string|max:2000',
            'project_sector' => 'required|string|max:100',
            'project_type' => 'required|string|max:100',
            'project_scale' => 'required|string|max:100',
            'project_location' => 'required|string|max:200',
            'project_duration' => 'required|string|max:200',
            'project_budget' => 'required|numeric|min:0',
            'project_funding_sources' => 'required|string|max:2000',
            'project_team_size' => 'required|integer|min:1',
            'project_team_experience' => 'required|string|max:2000',
            'project_previous_experience' => 'required|boolean',
            'project_previous_experience_details' => 'required|string|max:2000',
        ]);

        $submission = PorteurProjetSubmission::create([
            'user_id' => $request->user()->id,
            'project_title' => $request->project_title,
            'project_description' => $request->project_description,
            'project_sector' => $request->project_sector,
            'project_type' => $request->project_type,
            'project_scale' => $request->project_scale,
            'project_location' => $request->project_location,
            'project_duration' => $request->project_duration,
            'project_budget' => $request->project_budget,
            'project_funding_sources' => $request->project_funding_sources,
            'project_team_size' => $request->project_team_size,
            'project_team_experience' => $request->project_team_experience,
            'project_previous_experience' => $request->project_previous_experience,
            'project_previous_experience_details' => $request->project_previous_experience_details,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Project carrier submission created successfully',
            'data' => new PorteurProjetResource($submission),
        ], 201);
    }

    /**
     * Submit investment form.
     */
    public function submitInvestment(Request $request): JsonResponse
    {
        $request->validate([
            'investment_type' => 'required|string|max:100',
            'investment_amount' => 'required|numeric|min:0',
            'investment_sector' => 'required|string|max:100',
            'investment_location' => 'required|string|max:200',
            'investment_duration' => 'required|string|max:200',
            'investment_expected_return' => 'required|numeric|min:0',
            'investment_risk_level' => 'required|string|max:100',
            'investment_description' => 'required|string|max:2000',
            'investment_business_plan' => 'required|string|max:2000',
            'investment_financial_projections' => 'required|string|max:2000',
            'investment_market_analysis' => 'required|string|max:2000',
            'investment_team_experience' => 'required|string|max:2000',
            'investment_previous_experience' => 'required|boolean',
            'investment_previous_experience_details' => 'required|string|max:2000',
        ]);

        $submission = InvestmentSubmission::create([
            'user_id' => $request->user()->id,
            'investment_type' => $request->investment_type,
            'investment_amount' => $request->investment_amount,
            'investment_sector' => $request->investment_sector,
            'investment_location' => $request->investment_location,
            'investment_duration' => $request->investment_duration,
            'investment_expected_return' => $request->investment_expected_return,
            'investment_risk_level' => $request->investment_risk_level,
            'investment_description' => $request->investment_description,
            'investment_business_plan' => $request->investment_business_plan,
            'investment_financial_projections' => $request->investment_financial_projections,
            'investment_market_analysis' => $request->investment_market_analysis,
            'investment_team_experience' => $request->investment_team_experience,
            'investment_previous_experience' => $request->investment_previous_experience,
            'investment_previous_experience_details' => $request->investment_previous_experience_details,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Investment submission created successfully',
            'data' => new InvestmentResource($submission),
        ], 201);
    }

    /**
     * Submit INDH form.
     */
    public function submitINDH(Request $request): JsonResponse
    {
        $request->validate([
            'project_title' => 'required|string|max:255',
            'project_description' => 'required|string|max:2000',
            'project_sector' => 'required|string|max:100',
            'project_type' => 'required|string|max:100',
            'project_location' => 'required|string|max:200',
            'project_beneficiaries' => 'required|string|max:2000',
            'project_duration' => 'required|string|max:200',
            'project_budget' => 'required|numeric|min:0',
            'project_funding_requested' => 'required|numeric|min:0',
            'project_team_size' => 'required|integer|min:1',
            'project_team_experience' => 'required|string|max:2000',
            'project_previous_experience' => 'required|boolean',
            'project_previous_experience_details' => 'required|string|max:2000',
        ]);

        $submission = INDHSubmission::create([
            'user_id' => $request->user()->id,
            'project_title' => $request->project_title,
            'project_description' => $request->project_description,
            'project_sector' => $request->project_sector,
            'project_type' => $request->project_type,
            'project_location' => $request->project_location,
            'project_beneficiaries' => $request->project_beneficiaries,
            'project_duration' => $request->project_duration,
            'project_budget' => $request->project_budget,
            'project_funding_requested' => $request->project_funding_requested,
            'project_team_size' => $request->project_team_size,
            'project_team_experience' => $request->project_team_experience,
            'project_previous_experience' => $request->project_previous_experience,
            'project_previous_experience_details' => $request->project_previous_experience_details,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'INDH submission created successfully',
            'data' => new INDHResource($submission),
        ], 201);
    }

    /**
     * Submit training form.
     */
    public function submitTraining(Request $request): JsonResponse
    {
        $request->validate([
            'training_type' => 'required|string|max:100',
            'training_title' => 'required|string|max:255',
            'training_description' => 'required|string|max:2000',
            'training_sector' => 'required|string|max:100',
            'training_duration' => 'required|string|max:200',
            'training_location' => 'required|string|max:200',
            'training_participants' => 'required|string|max:2000',
            'training_budget' => 'required|numeric|min:0',
            'training_funding_requested' => 'required|numeric|min:0',
            'training_team_size' => 'required|integer|min:1',
            'training_team_experience' => 'required|string|max:2000',
            'training_previous_experience' => 'required|boolean',
            'training_previous_experience_details' => 'required|string|max:2000',
        ]);

        $submission = TrainingSubmission::create([
            'user_id' => $request->user()->id,
            'training_type' => $request->training_type,
            'training_title' => $request->training_title,
            'training_description' => $request->training_description,
            'training_sector' => $request->training_sector,
            'training_duration' => $request->training_duration,
            'training_location' => $request->training_location,
            'training_participants' => $request->training_participants,
            'training_budget' => $request->training_budget,
            'training_funding_requested' => $request->training_funding_requested,
            'training_team_size' => $request->training_team_size,
            'training_team_experience' => $request->training_team_experience,
            'training_previous_experience' => $request->training_previous_experience,
            'training_previous_experience_details' => $request->training_previous_experience_details,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Training submission created successfully',
            'data' => new TrainingResource($submission),
        ], 201);
    }
}