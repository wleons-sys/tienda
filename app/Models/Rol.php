<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'idRol';
    public $timestamps = true;

    protected $fillable = ['nombre', 'descripcion', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación con usuarios
     */
    public function usuarios()
    {
        return $this->belongsToMany(
            Usuario::class,
            'roles_usuarios',
            'idRol',
            'idUsuario'
        )->withTimestamps();
    }
}