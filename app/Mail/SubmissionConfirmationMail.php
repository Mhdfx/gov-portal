<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmissionConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $submission;
    public $submissionType;
    public $trackingNumber;

    /**
     * Create a new message instance.
     */
    public function __construct($submission, $submissionType, $trackingNumber = null)
    {
        $this->submission = $submission;
        $this->submissionType = $submissionType;
        $this->trackingNumber = $trackingNumber;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de Soumission - Plateforme Boiema',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.submission-confirmation',
            with: [
                'submission' => $this->submission,
                'submissionType' => $this->submissionType,
                'trackingNumber' => $this->trackingNumber,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}






























