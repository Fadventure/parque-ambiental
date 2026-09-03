<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'zona_id',
        'telefono',
        'direccion',
        'fecha_contratacion',
        'tarea',
    ];

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con Zona
    public function zona()
    {
        return $this->belongsTo(Zona::class);
    }
}