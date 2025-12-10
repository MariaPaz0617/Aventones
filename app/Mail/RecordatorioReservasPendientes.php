<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecordatorioReservasPendientes extends Mailable
{
    use Queueable, SerializesModels;

    public $chofer;
    public $reservas;

    public function __construct($chofer, $reservas)
    {
        $this->chofer = $chofer;
        $this->reservas = $reservas;
    }

    public function build()
    {
        return $this->subject('Tienes reservas pendientes por gestionar')
            ->markdown('emails.reservas.pendientes');
    }
}
