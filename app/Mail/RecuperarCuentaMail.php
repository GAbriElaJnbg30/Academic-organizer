<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecuperarCuentaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usuario, $token)
    {
        $this->usuario = $usuario;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Recuperación de cuenta - Academic Organizer')
                    ->view('emails.recuperar_cuenta')
                    ->with([
                        'nombre' => $this->usuario->nombre,
                        'link' => url('/recuperar-password?token=' . $this->token),
                    ]);
    }
}
