<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recordatorio extends Model
{
    use HasFactory;

    // Especificamos la tabla
    protected $table = 'Recordatorio';
    protected $primaryKey = 'id_recordatorio';

    // Definir los nombres de las columnas de marcas de tiempo
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    // Si usas las columnas con nombres personalizados, deshabilitar timestamps automáticos
    public $timestamps = true;

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'id_recordatorio',
        'titulo',
        'fecha',
        'hora',
        'descripcion',
        'recordatorio_activado',
        'id_usuario'
    ];

    // Relación con el modelo Usuario
    public function usuario()
    {
       // return $this->belongsTo(Usuario::class, 'id_usuario');
       return $this->belongsTo(User::class, 'id_usuario', 'id'); // Asegúrate de que 'usuario_id' sea la clave foránea
    }
    
}
