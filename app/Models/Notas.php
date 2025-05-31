<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notas extends Model
{
    protected $table = 'Nota'; // nombre exacto de la tabla

    protected $primaryKey = 'id_nota'; // clave primaria personalizada

    public $timestamps = false; // desactiva timestamps automáticos

    protected $fillable = [
        'nombre_nota',
        'tipo',
        'ruta_nota',
        'id_usuario',
        'fecha_modificacion',
        'contenido_html', // <-- agregar esto'
        //'id_carpetaN',
    ];
    

    // Relación: una nota pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    // Relación: una nota pertenece a una carpeta
    public function carpeta()
    {
        return $this->belongsTo(Carpeta::class, 'id_carpetaN');
    }

}