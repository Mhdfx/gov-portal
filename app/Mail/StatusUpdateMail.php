<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $submission;
    public $submissionType;
    public $oldStatus;
    public $newStatus;
    public $adminNotes;

    /**
     * Create a new message instance.
     */
    public function __construct($submission, $submissionType, $oldStatus, $newStatus, $adminNotes = null)
    {
        $this->submission = $submission;
        $this->submissionType = $submissionType;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->adminNotes = $adminNotes;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mise à Jour du Statut - Plateforme Boiema',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.status-update',
            with: [
                'submission' => $this->submission,
                'submissionType' => $this->submissionType,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
                'adminNotes' => $this->adminNotes,
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






























