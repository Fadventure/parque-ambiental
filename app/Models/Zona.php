<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    protected $fillable = ['nombre', 'administrador_id', 'humedad', 'temperatura', 'tiene_alerta'];
}
