<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    use HasFactory;

    protected $table = 'Archivos'; // Bien, si tu tabla se llama exactamente así con mayúscula

    protected $primaryKey = 'id_archivo';

    public $timestamps = false; // Correcto, ya que no tienes 'created_at' ni 'updated_at'

    protected $fillable = [
        'nombre_archivo',
        'tipo_archivo',
        'tamaño_archivo',
        'ruta',
        'id_carpeta' // ✅ OJO: no incluyas fecha_subida si se genera automáticamente
    ];
}