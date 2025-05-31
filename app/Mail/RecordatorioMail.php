<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecordatorioMail extends Mailable
{
    use Queueable, SerializesModels;

    public $recordatorio;

    /**
     * Create a new message instance.
     *
     * @param $recordatorio
     */
    public function __construct($recordatorio)
    {
        $this->recordatorio = $recordatorio;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Recordatorio de Actividad')
                    ->view('emails.recordatorio')
                    ->with([
                        'titulo' => $this->recordatorio->titulo,
                        'fecha' => $this->recordatorio->fecha,
                        'hora' => $this->recordatorio->hora,
                        'descripcion' => $this->recordatorio->descripcion,
                    ]);
    }
}
