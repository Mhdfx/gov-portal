<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\SubmissionConfirmationMail;
use App\Mail\StatusUpdateMail;
use App\Models\User;

class EmailNotificationService
{
    /**
     * Send submission confirmation email
     */
    public function sendSubmissionConfirmation($submission, $submissionType, $trackingNumber = null)
    {
        try {
            $user = User::find($submission->user_id);
            
            if ($user && $user->email) {
                Mail::to($user->email)->send(
                    new SubmissionConfirmationMail($submission, $submissionType, $trackingNumber)
                );
                
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            \Log::error('Failed to send submission confirmation email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send status update email
     */
    public function sendStatusUpdate($submission, $submissionType, $oldStatus, $newStatus, $adminNotes = null)
    {
        try {
            $user = User::find($submission->user_id);
            
            if ($user && $user->email) {
                Mail::to($user->email)->send(
                    new StatusUpdateMail($submission, $submissionType, $oldStatus, $newStatus, $adminNotes)
                );
                
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            \Log::error('Failed to send status update email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send bulk status update emails
     */
    public function sendBulkStatusUpdate($submissions, $submissionType, $oldStatus, $newStatus, $adminNotes = null)
    {
        $successCount = 0;
        $failureCount = 0;

        foreach ($submissions as $submission) {
            if ($this->sendStatusUpdate($submission, $submissionType, $oldStatus, $newStatus, $adminNotes)) {
                $successCount++;
            } else {
                $failureCount++;
            }
        }

        return [
            'success_count' => $successCount,
            'failure_count' => $failureCount,
        ];
    }

    /**
     * Send welcome email to new users
     */
    public function sendWelcomeEmail($user)
    {
        try {
            if ($user && $user->email) {
                Mail::to($user->email)->send(new \App\Mail\WelcomeMail($user));
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send company approval email
     */
    public function sendCompanyApprovalEmail($company, $approved = true)
    {
        try {
            $user = User::find($company->user_id);
            
            if ($user && $user->email) {
                if ($approved) {
                    Mail::to($user->email)->send(new \App\Mail\CompanyApprovedMail($company));
                } else {
                    Mail::to($user->email)->send(new \App\Mail\CompanyRejectedMail($company));
                }
                
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            \Log::error('Failed to send company approval email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send training notification email
     */
    public function sendTrainingNotification($training, $users)
    {
        try {
            $successCount = 0;
            
            foreach ($users as $user) {
                if ($user->email) {
                    Mail::to($user->email)->send(new \App\Mail\TrainingNotificationMail($training));
                    $successCount++;
                }
            }
            
            return $successCount;
        } catch (\Exception $e) {
            \Log::error('Failed to send training notification emails: ' . $e->getMessage());
            return 0;
        }
    }
}






























