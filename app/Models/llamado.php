<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Llamado extends Model
{
    use HasFactory;

    protected $fillable = [
        'zona_id',
        'user_id',
        'tipo',
        'estado',
        'descripcion',
    ];

    public function zona()
    {
        return $this->belongsTo(Zona::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}