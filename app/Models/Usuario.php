<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Usuario extends Model implements \Illuminate\Contracts\Auth\Authenticatable
{
    use AuthenticatableTrait;
    
    public $timestamps = false; // Desactiva las marcas de tiempo
    protected $table = 'Usuario'; // Especifica el nombre exacto de la tabla
    protected $primaryKey = 'id_usuario';
    public $contraseña_confirmation;

    protected $fillable = [
        'id_usuario',
        'nombre',
        'apellido',
        'nombre_usuario',
        'correo_electronico',
        'contraseña',
        'fecha_nacimiento',
        'genero',
        'telefono',
        'rol',
        'foto_perfil'
    ];

    // Opcional: Define la relación con la tabla 'Rol'
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol');
    }

    public function up()
    {
        Schema::table('Usuario', function (Blueprint $table) {
            $table->timestamps(); // Agrega columnas created_at y updated_at
        });
    }

    public function down()
    {
        Schema::table('Usuario', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }


}
