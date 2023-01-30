<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $invoiceId;

    /**
     * Create a new message instance.
     *
     * @param int $invoiceId
     *
     * @return void
     */
    public function __construct(
        int $invoiceId
    )
    {
        $this->invoiceId = $invoiceId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user.emails.sendInvoice', [
            'invoiceId' => $this->invoiceId,
        ]);
    }
}
