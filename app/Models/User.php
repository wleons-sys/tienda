<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'email',
        'password',
        'activo',
        'intentos_fallidos',
        'bloqueado_hasta',
        'ultima_actividad',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'activo' => 'boolean',
        'ultima_actividad' => 'datetime',
        'bloqueado_hasta' => 'datetime',
    ];

    /**
     * Relación con roles
     */
    public function roles()
    {
        return $this->belongsToMany(
            Rol::class,
            'roles_usuarios',
            'idUsuario',
            'idRol'
        )->withTimestamps();
    }

    /**
     * Verificar si tiene un rol específico
     */
    public function tieneRol(string $rolNombre): bool
    {
        return $this->roles()->where('nombre', $rolNombre)->exists();
    }

    /**
     * Verificar si es Administrador
     */
    public function esAdministrador(): bool
    {
        return $this->tieneRol('Administrador');
    }

    /**
     * Verificar si es Vendedor
     */
    public function esVendedor(): bool
    {
        return $this->tieneRol('Vendedor');
    }
}