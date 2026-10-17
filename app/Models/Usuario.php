<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'idUsuario';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'activo',
        'ultima_actividad',
        'intentos_fallidos',
        'bloqueado_hasta',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'ultima_actividad' => 'datetime',
        'bloqueado_hasta' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(
            Rol::class,
            'roles_usuarios',
            'idUsuario',
            'idRol'
        )->withTimestamps();
    }

    public function tieneRol(string $rolNombre): bool
    {
        return $this->roles()->where('nombre', $rolNombre)->exists();
    }

    public function esAdministrador(): bool
    {
        return $this->tieneRol('Administrador');
    }

    public function esVendedor(): bool
    {
        return $this->tieneRol('Vendedor');
    }
}