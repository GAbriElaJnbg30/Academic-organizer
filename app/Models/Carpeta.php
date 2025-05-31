<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carpeta extends Model
{
    use HasFactory;

    // Asegurarse de que el nombre de la tabla sea en plural, según la convención de Laravel
    protected $table = 'Carpeta'; // La tabla debería estar en minúsculas y plural, por convención
    protected $primaryKey = 'id_carpeta';

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'id_carpeta',
        'nombre_carpeta', 
        'fecha_creacion', 
        'id_usuario',
        'parent_id'
    ];

    // Si no deseas usar los timestamps, mantén public $timestamps = false;
    public $timestamps = false; // Solo si no usas las columnas 'created_at' y 'updated_at'

    // Definir la relación con el modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // ---------------------------------- Relación con archivos -----------------------------------
    public function archivos()
    {
        return $this->hasMany(Archivo::class, 'id_carpeta');
    }

    // Relación con subcarpetas (si aplica)
    public function subcarpetas()
    {
        return $this->hasMany(Carpeta::class, 'parent_id');
    }

    // Relación inversa: Una carpeta pertenece a una carpeta principal
    public function carpetaPadre()
    {
        return $this->belongsTo(Carpeta::class, 'parent_id');
    }
}
