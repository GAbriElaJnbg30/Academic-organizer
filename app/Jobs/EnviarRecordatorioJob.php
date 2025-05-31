<?php

namespace App\Jobs;

use App\Mail\RecordatorioMail;
use App\Models\Recordatorio;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class EnviarRecordatorioJob
{
    use Queueable, SerializesModels;

    public $recordatorio;

    /**
     * Crear una nueva instancia del trabajo.
     *
     * @param Recordatorio $recordatorio
     */
    public function __construct(Recordatorio $recordatorio)
    {
        $this->recordatorio = $recordatorio;
    }

    /**
     * Ejecutar el trabajo.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('Ejecutando job para el recordatorio ID: ' . $this->recordatorio->id_recordatorio);
        
        $user = $this->recordatorio->usuario;
        if ($user) {
            \Log::info('Enviando correo a: ' . $user->correo_electronico);
            Mail::to($user->correo_electronico)->send(new RecordatorioMail($this->recordatorio));
        } else {
            \Log::warning('No se encontró el usuario para el recordatorio ID: ' . $this->recordatorio->id_recordatorio);
        }
        
    }
    
}
