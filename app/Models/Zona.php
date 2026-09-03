<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    protected $fillable = ['nombre', 'administrador_id', 'humedad', 'temperatura', 'tiene_alerta'];

    public function administrador()
    {
        return $this->belongsTo(User::class, 'administrador_id');
    }
}