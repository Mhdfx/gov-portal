<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\InvestmentSubmission;
use App\Models\ProjectCarrierSubmission;
use App\Models\IdeaCarrierSubmission;
use App\Models\AutoEntrepreneurSubmission;
use App\Models\INDHSubmission;
use App\Models\TrainingSubmission;
use App\Models\FormRoutingRule;
use App\Models\SubmissionRoutingLog;
use App\Http\Requests\InvestmentSubmissionRequest;
use App\Http\Requests\ProjectCarrierRequest;
use App\Http\Requests\IdeaCarrierRequest;
use App\Http\Requests\AutoEntrepreneurSubmissionRequest;
use App\Http\Requests\INDHSubmissionRequest;
use App\Http\Requests\TrainingSubmissionRequest;
use App\Constants\AppConstants;
use App\Services\LoggingService;
use App\Services\FileUploadService;
use App\Events\NewSubmissionCreated;

class FormSubmissionController extends Controller
{
    protected LoggingService $loggingService;
    protected FileUploadService $fileUploadService;

    public function __construct(LoggingService $loggingService, FileUploadService $fileUploadService)
    {
        $this->loggingService = $loggingService;
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Submit investment form.
     * 
     * Handles investment form submission with validation, file uploads (if any),
     * submission routing, and logging. Supports both AJAX and regular form submissions.
     * 
     * @param InvestmentSubmissionRequest $request Validated form request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function submitInvestment(InvestmentSubmissionRequest $request)
    {
        try {
            $submission = InvestmentSubmission::create([
                'user_id' => Auth::id(),
                'submission_number' => $this->generateSubmissionNumber('INV'),
                'project_name' => $request->project_name,
                'project_description' => $request->project_description,
                'investment_amount' => $request->investment_amount,
                'currency' => $request->currency,
                'investment_type' => $request->investment_type,
                'sector' => $request->sector,
                'region' => $request->region,
                'city' => $request->city,
                'business_plan' => $request->business_plan,
                'financial_projections' => $request->financial_projections,
                'contact_person' => $request->first_name . ' ' . $request->last_name,
                'contact_email' => $request->email,
                'contact_phone' => $request->phone,
                'status' => AppConstants::STATUS_PENDING,
                'submitted_at' => now(),
            ]);

            // Route submission to appropriate institutions
            $this->routeSubmission($submission, 'investment');

            // Log the submission
            $this->loggingService->logFormSubmission('investment', $submission->id);

            // Handle both AJAX and regular form submissions
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Investment submission created successfully',
                    'submission_number' => $submission->submission_number,
                    'status' => 'success'
                ], 201);
            }

            return redirect()->route('user.submissions')
                ->with('success', 'Investment submission created successfully! Submission number: ' . $submission->submission_number);
        } catch (\Exception $e) {
            $this->loggingService->logError('Failed to submit investment form', $e);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'An error occurred while submitting the form. Please try again.',
                    'status' => 'error'
                ], 500);
            }

            return back()->with('error', 'An error occurred while submitting the form. Please try again.');
        }
    }

    /**
     * Submit project carrier form.
     */
    public function submitProjectCarrier(ProjectCarrierRequest $request)
    {
        try {
            // Handle file uploads with security validation
            $cvPath = null;
            $businessPlanPath = null;
            $financialProjectionsPath = null;
            $marketAnalysisPath = null;
            $technicalDocumentationPath = null;

            if ($request->hasFile('cv')) {
                if ($this->fileUploadService->isExecutable($request->file('cv'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                $result = $this->fileUploadService->uploadFile(
                    $request->file('cv'),
                    'project-carrier/cv',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                if ($result['success']) {
                    $cvPath = $result['path'];
                }
            }

            if ($request->hasFile('business_plan')) {
                if ($this->fileUploadService->isExecutable($request->file('business_plan'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                $result = $this->fileUploadService->uploadFile(
                    $request->file('business_plan'),
                    'project-carrier/business-plans',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                if ($result['success']) {
                    $businessPlanPath = $result['path'];
                }
            }

            if ($request->hasFile('financial_projections')) {
                if ($this->fileUploadService->isExecutable($request->file('financial_projections'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                $result = $this->fileUploadService->uploadFile(
                    $request->file('financial_projections'),
                    'project-carrier/financial',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                if ($result['success']) {
                    $financialProjectionsPath = $result['path'];
                }
            }

            if ($request->hasFile('market_analysis')) {
                if ($this->fileUploadService->isExecutable($request->file('market_analysis'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                $result = $this->fileUploadService->uploadFile(
                    $request->file('market_analysis'),
                    'project-carrier/analysis',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                if ($result['success']) {
                    $marketAnalysisPath = $result['path'];
                }
            }

            if ($request->hasFile('technical_documentation')) {
                if ($this->fileUploadService->isExecutable($request->file('technical_documentation'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                $result = $this->fileUploadService->uploadFile(
                    $request->file('technical_documentation'),
                    'project-carrier/technical',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                if ($result['success']) {
                    $technicalDocumentationPath = $result['path'];
                }
            }

            $submission = ProjectCarrierSubmission::create([
                'user_id' => Auth::id(),
                'submission_number' => $this->generateSubmissionNumber('PRJ'),
                'project_name' => $request->project_name,
                'project_description' => $request->project_description,
                'sector' => $request->sector,
                'development_stage' => $request->development_stage,
                'team_size' => $request->team_size ?? 1,
                'funding_required' => $request->funding_required,
                'funding_currency' => $request->funding_currency,
                'business_plan_path' => $businessPlanPath,
                'location_region' => $request->location_region,
                'location_city' => $request->location_city,
                'status' => AppConstants::STATUS_PENDING,
                'submitted_at' => now(),
            ]);

            // Route submission to appropriate institutions
            $this->routeSubmission($submission, 'project_carrier');

            // Log the submission
            $this->loggingService->logFormSubmission('project_carrier', $submission->id);

            // Handle both AJAX and regular form submissions
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Project carrier submission created successfully',
                    'submission_number' => $submission->submission_number,
                    'status' => 'success'
                ], 201);
            }

            return redirect()->route('user.submissions')
                ->with('success', 'Project carrier submission created successfully! Submission number: ' . $submission->submission_number);

        } catch (\Exception $e) {
            $this->loggingService->logError('Failed to submit project carrier form', $e);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'An error occurred while submitting the form. Please try again.',
                    'status' => 'error'
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while submitting the form. Please try again.');
        }
    }

    /**
     * Submit idea carrier form.
     */
    public function submitIdeaCarrier(IdeaCarrierRequest $request)
    {
        try {
            // Handle file uploads with security validation
            $cvPath = null;
            $ideaDocumentPath = null;
            $marketResearchPath = null;
            $prototypeImagesPath = null;

            if ($request->hasFile('cv')) {
                if ($this->fileUploadService->isExecutable($request->file('cv'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                $result = $this->fileUploadService->uploadFile(
                    $request->file('cv'),
                    'idea-carrier/cv',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                if ($result['success']) {
                    $cvPath = $result['path'];
                }
            }

            if ($request->hasFile('idea_document')) {
                if ($this->fileUploadService->isExecutable($request->file('idea_document'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                $result = $this->fileUploadService->uploadFile(
                    $request->file('idea_document'),
                    'idea-carrier/documents',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                if ($result['success']) {
                    $ideaDocumentPath = $result['path'];
                }
            }

            if ($request->hasFile('market_research')) {
                if ($this->fileUploadService->isExecutable($request->file('market_research'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                $result = $this->fileUploadService->uploadFile(
                    $request->file('market_research'),
                    'idea-carrier/research',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                if ($result['success']) {
                    $marketResearchPath = $result['path'];
                }
            }

            if ($request->hasFile('prototype_images')) {
                if ($this->fileUploadService->isExecutable($request->file('prototype_images'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                $result = $this->fileUploadService->uploadFile(
                    $request->file('prototype_images'),
                    'idea-carrier/prototypes',
                    array_merge(
                        $this->fileUploadService->getAllowedImageMimeTypes(),
                        $this->fileUploadService->getAllowedDocumentMimeTypes()
                    ),
                    AppConstants::MAX_FILE_SIZE
                );
                if ($result['success']) {
                    $prototypeImagesPath = $result['path'];
                }
            }

            $submission = IdeaCarrierSubmission::create([
                'user_id' => Auth::id(),
                'submission_number' => $this->generateSubmissionNumber('IDEA'),
                'idea_title' => $request->idea_title,
                'idea_description' => $request->idea_description,
                'sector' => $request->sector,
                'development_level' => $request->development_level,
                'support_needed' => $request->support_needed,
                'budget_estimate' => $request->budget_estimate,
                'budget_currency' => $request->budget_currency ?? 'MAD',
                'location_region' => $request->location_region,
                'location_city' => $request->location_city,
                'status' => AppConstants::STATUS_PENDING,
                'submitted_at' => now(),
            ]);

            // Route submission to appropriate institutions
            $this->routeSubmission($submission, 'idea_carrier');
            
            // Fire event for real-time updates
            event(new NewSubmissionCreated($submission, Auth::id()));

            // Log the submission
            $this->loggingService->logFormSubmission('idea_carrier', $submission->id);

            // Handle both AJAX and regular form submissions
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Idea carrier submission created successfully',
                    'submission_number' => $submission->submission_number,
                    'status' => 'success'
                ], 201);
            }

            return redirect()->route('user.submissions')
                ->with('success', 'Idea carrier submission created successfully! Submission number: ' . $submission->submission_number);

        } catch (\Exception $e) {
            $this->loggingService->logError('Failed to submit idea carrier form', $e);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'An error occurred while submitting the form. Please try again.',
                    'status' => 'error'
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while submitting the form. Please try again.');
        }
    }

    /**
     * Submit auto entrepreneur form.
     */
    public function submitAutoEntrepreneur(AutoEntrepreneurSubmissionRequest $request)
    {
        try {
            // Handle file uploads with security validation
            $identityDocumentPath = null;
            $businessPlanPath = null;
            $financialProjectionsPath = null;
            $cvPath = null;

            if ($request->hasFile('identity_document')) {
                // Check if file is executable (security check)
                if ($this->fileUploadService->isExecutable($request->file('identity_document'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                
                $allowedMimeTypes = array_merge(
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    $this->fileUploadService->getAllowedImageMimeTypes()
                );
                
                $result = $this->fileUploadService->uploadFile(
                    $request->file('identity_document'),
                    'auto-entrepreneur/documents',
                    $allowedMimeTypes,
                    AppConstants::MAX_FILE_SIZE
                );
                
                if (!$result['success']) {
                    throw new \Exception($result['error']);
                }
                
                $identityDocumentPath = $result['path'];
            }

            if ($request->hasFile('business_plan')) {
                if ($this->fileUploadService->isExecutable($request->file('business_plan'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                
                $result = $this->fileUploadService->uploadFile(
                    $request->file('business_plan'),
                    'auto-entrepreneur/business-plans',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                
                if ($result['success']) {
                    $businessPlanPath = $result['path'];
                }
            }

            if ($request->hasFile('financial_projections')) {
                if ($this->fileUploadService->isExecutable($request->file('financial_projections'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                
                $result = $this->fileUploadService->uploadFile(
                    $request->file('financial_projections'),
                    'auto-entrepreneur/financial',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                
                if ($result['success']) {
                    $financialProjectionsPath = $result['path'];
                }
            }

            if ($request->hasFile('cv')) {
                if ($this->fileUploadService->isExecutable($request->file('cv'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                
                $result = $this->fileUploadService->uploadFile(
                    $request->file('cv'),
                    'auto-entrepreneur/cv',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                
                if ($result['success']) {
                    $cvPath = $result['path'];
                }
            }

            $submission = AutoEntrepreneurSubmission::create([
                'user_id' => Auth::id(),
                'submission_number' => $this->generateSubmissionNumber('AUTO'),
                'business_name' => $request->business_name,
                'business_description' => $request->business_description,
                'sector' => $request->business_sector,
                'business_type' => $request->business_type,
                'startup_capital' => $request->initial_investment,
                'capital_currency' => $request->funding_source === 'personal_savings' ? 'MAD' : 'MAD', // Default to MAD
                'cv_path' => $cvPath,
                'business_plan_path' => $businessPlanPath,
                'location_region' => $request->business_region ?? $request->region,
                'location_city' => $request->business_city ?? $request->city,
                'status' => AppConstants::STATUS_PENDING,
                'submitted_at' => now(),
            ]);

            // Route submission to appropriate institutions
            $this->routeSubmission($submission, 'auto_entrepreneur');
            
            // Fire event for real-time updates
            event(new NewSubmissionCreated($submission, Auth::id()));

            // Log the submission
            $this->loggingService->logFormSubmission('auto_entrepreneur', $submission->id);

            // Handle both AJAX and regular form submissions
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Auto entrepreneur submission created successfully',
                    'submission_number' => $submission->submission_number,
                    'status' => 'success'
                ], 201);
            }

            return redirect()->route('user.submissions')
                ->with('success', 'Auto entrepreneur submission created successfully! Submission number: ' . $submission->submission_number);

        } catch (\Exception $e) {
            $this->loggingService->logError('Failed to submit auto entrepreneur form', $e);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'An error occurred while submitting the form. Please try again.',
                    'status' => 'error'
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while submitting the form. Please try again.');
        }
    }

    /**
     * Submit INDH form.
     */
    public function submitINDH(INDHSubmissionRequest $request)
    {
        try {
            // Handle file uploads with security validation
            $cvPath = null;
            $projectProposalPath = null;
            $budgetDetailedPath = null;
            $communityLettersPath = null;
            $partnershipAgreementsPath = null;

            if ($request->hasFile('cv')) {
                if ($this->fileUploadService->isExecutable($request->file('cv'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                $result = $this->fileUploadService->uploadFile(
                    $request->file('cv'),
                    'indh/cv',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                if ($result['success']) {
                    $cvPath = $result['path'];
                }
            }

            if ($request->hasFile('project_proposal')) {
                if ($this->fileUploadService->isExecutable($request->file('project_proposal'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                $result = $this->fileUploadService->uploadFile(
                    $request->file('project_proposal'),
                    'indh/proposals',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                if ($result['success']) {
                    $projectProposalPath = $result['path'];
                }
            }

            if ($request->hasFile('budget_detailed')) {
                if ($this->fileUploadService->isExecutable($request->file('budget_detailed'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                $result = $this->fileUploadService->uploadFile(
                    $request->file('budget_detailed'),
                    'indh/budgets',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                if ($result['success']) {
                    $budgetDetailedPath = $result['path'];
                }
            }

            if ($request->hasFile('community_letters')) {
                if ($this->fileUploadService->isExecutable($request->file('community_letters'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                $result = $this->fileUploadService->uploadFile(
                    $request->file('community_letters'),
                    'indh/letters',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                if ($result['success']) {
                    $communityLettersPath = $result['path'];
                }
            }

            if ($request->hasFile('partnership_agreements')) {
                if ($this->fileUploadService->isExecutable($request->file('partnership_agreements'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                $result = $this->fileUploadService->uploadFile(
                    $request->file('partnership_agreements'),
                    'indh/agreements',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                if ($result['success']) {
                    $partnershipAgreementsPath = $result['path'];
                }
            }

            $submission = INDHSubmission::create([
            'user_id' => Auth::id(),
            'submission_number' => $this->generateSubmissionNumber('INDH'),
            'project_title' => $request->project_title,
            'project_description' => $request->project_description,
            'project_type' => $request->project_type,
            'community_impact' => $request->community_impact,
            'target_beneficiaries' => $request->target_beneficiaries,
            'funding_required' => $request->funding_required,
            'funding_currency' => $request->funding_currency,
            'project_duration_months' => $request->project_duration_months,
            'location_region' => $request->location_region,
            'location_city' => $request->location_city,
            'status' => AppConstants::STATUS_PENDING,
            'submitted_at' => now(),
        ]);

            // Route submission to appropriate institutions
            $this->routeSubmission($submission, 'indh');
            
            // Fire event for real-time updates
            event(new NewSubmissionCreated($submission, Auth::id()));

            // Log the submission
            $this->loggingService->logFormSubmission('indh', $submission->id);

            // Handle both AJAX and regular form submissions
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'INDH submission created successfully',
                    'submission_number' => $submission->submission_number,
                    'status' => 'success'
                ], 201);
            }

            return redirect()->route('user.submissions')
                ->with('success', 'INDH submission created successfully! Submission number: ' . $submission->submission_number);
        } catch (\Exception $e) {
            $this->loggingService->logError('Failed to submit INDH form', $e);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'An error occurred while submitting the form. Please try again.',
                    'status' => 'error'
                ], 500);
            }

            return back()->with('error', 'An error occurred while submitting the form. Please try again.');
        }
    }

    /**
     * Submit training form.
     */
    public function submitTraining(TrainingSubmissionRequest $request)
    {
        try {
            // Handle file uploads with security validation
            $cvPath = null;
            $motivationLetterPath = null;
            $previousCertificatesPath = null;
            $employerApprovalPath = null;

            if ($request->hasFile('cv')) {
                if ($this->fileUploadService->isExecutable($request->file('cv'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                $result = $this->fileUploadService->uploadFile(
                    $request->file('cv'),
                    'training/cv',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                if ($result['success']) {
                    $cvPath = $result['path'];
                }
            }

            if ($request->hasFile('motivation_letter')) {
                if ($this->fileUploadService->isExecutable($request->file('motivation_letter'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                $result = $this->fileUploadService->uploadFile(
                    $request->file('motivation_letter'),
                    'training/letters',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                if ($result['success']) {
                    $motivationLetterPath = $result['path'];
                }
            }

            if ($request->hasFile('previous_certificates')) {
                if ($this->fileUploadService->isExecutable($request->file('previous_certificates'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                $result = $this->fileUploadService->uploadFile(
                    $request->file('previous_certificates'),
                    'training/certificates',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                if ($result['success']) {
                    $previousCertificatesPath = $result['path'];
                }
            }

            if ($request->hasFile('employer_approval')) {
                if ($this->fileUploadService->isExecutable($request->file('employer_approval'))) {
                    throw new \Exception('Executable files are not allowed');
                }
                $result = $this->fileUploadService->uploadFile(
                    $request->file('employer_approval'),
                    'training/approvals',
                    $this->fileUploadService->getAllowedDocumentMimeTypes(),
                    AppConstants::MAX_FILE_SIZE
                );
                if ($result['success']) {
                    $employerApprovalPath = $result['path'];
                }
            }

            $submission = TrainingSubmission::create([
            'user_id' => Auth::id(),
            'submission_number' => $this->generateSubmissionNumber('TRN'),
            'training_title' => $request->training_title,
            'training_description' => $request->training_description,
            'training_type' => $request->training_type,
            'target_audience' => $request->target_audience,
            'participant_count' => $request->participant_count,
            'duration_hours' => $request->duration_hours,
            'preferred_location' => $request->preferred_location,
            'preferred_schedule' => $request->preferred_schedule,
            'budget_available' => $request->budget_available,
            'budget_currency' => $request->budget_currency,
            'status' => AppConstants::STATUS_PENDING,
            'submitted_at' => now(),
        ]);

            // Route submission to appropriate institutions
            $this->routeSubmission($submission, 'training');
            
            // Fire event for real-time updates
            event(new NewSubmissionCreated($submission, Auth::id()));

            // Log the submission
            $this->loggingService->logFormSubmission('training', $submission->id);

            // Handle both AJAX and regular form submissions
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Training submission created successfully',
                    'submission_number' => $submission->submission_number,
                    'status' => 'success'
                ], 201);
            }

            return redirect()->route('user.submissions')
                ->with('success', 'Training submission created successfully! Submission number: ' . $submission->submission_number);
        } catch (\Exception $e) {
            $this->loggingService->logError('Failed to submit training form', $e);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'An error occurred while submitting the form. Please try again.',
                    'status' => 'error'
                ], 500);
            }

            return back()->with('error', 'An error occurred while submitting the form. Please try again.');
        }
    }

    /**
     * Generate unique submission number.
     */
    private function generateSubmissionNumber($prefix)
    {
        $year = date('Y');
        $month = date('m');
        $sequence = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return $prefix . $year . $month . $sequence;
    }

    /**
     * Route submission to appropriate institutions.
     */
    private function routeSubmission($submission, $formType)
    {
        $rules = FormRoutingRule::active()
            ->byFormType($formType)
            ->orderedByPriority()
            ->get();

        $routedInstitutions = [];

        foreach ($rules as $rule) {
            if ($rule->matches($formType, $submission->region ?? $submission->location_region, $submission->sector)) {
                // Log the routing
                SubmissionRoutingLog::create([
                    'submission_id' => $submission->id,
                    'submission_type' => $formType,
                    'institution_id' => $rule->institution_id,
                    'status' => 'sent',
                    'routed_at' => now(),
                ]);

                $routedInstitutions[] = $rule->institution_id;
            }
        }

        // Update submission with routing information (only if columns exist)
        $updateData = [];
        
        try {
            if (Schema::hasColumn($submission->getTable(), 'routing_institutions')) {
                $updateData['routing_institutions'] = $routedInstitutions;
            }
            
            if (Schema::hasColumn($submission->getTable(), 'tracking_number')) {
                $updateData['tracking_number'] = $this->generateTrackingNumber();
            }
            
            if (!empty($updateData)) {
                $submission->update($updateData);
            }
        } catch (\Exception $e) {
            // If update fails (e.g., column doesn't exist), just log and continue
            \Log::warning('Failed to update submission routing info: ' . $e->getMessage());
        }
    }

    /**
     * Generate tracking number.
     */
    private function generateTrackingNumber()
    {
        return 'TRK' . date('Ymd') . rand(1000, 9999);
    }
}
