<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PersonaPnf;

class Persona extends Model
{
    use HasFactory;


    public $timestamps = false; // Disable timestamps if not needed
    protected $table = "persona";
    //Definir clave primaria
    protected $primaryKey = "id_persona";
    
    protected $guarded = ['id_persona']; // solo bloquea el id_persona

     public function pnfs()
    {
        return $this->belongsTo(PersonaPnf::class, 'id_persona', 'persona_pnf', 'id_pnf');
    }

    public function personaForanea()
    {
        return $this->hasOne(PersonaForanea::class, 'id_persona');
    }

    public function becaSoli()
    {
        return $this->hasMany(BecaSoli::class, 'id_persona');
    }

    public function direccion()
    {
        return $this->hasMany(Direccion::class, 'id_persona');
    }
    public function perfil()
    {
        return $this->belongsTo(Perfil::class, 'id_perfil');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'id_sede');
    }

    public function condicionEstudiante()
    {
        return $this->hasMany(CondicionEstudiante::class, 'id_persona');
    }

    public function regisDiarioComedor()
    {
        return $this->hasMany(RegisDiarioComedor::class, 'id_persona');
    }

    public function users()
    {
        return $this->hasOne(User::class, 'id_persona');
    }
}
