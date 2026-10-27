<?php

namespace App\Mail;

use App\Models\Izin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PermitReadyForHandoverMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public bool $afterCommit = true;

    public function __construct(public Izin $izin) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Izin Anda Siap Diserahkan');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.permit-ready-for-handover');
    }

    public function attachments(): array { return []; }
}
