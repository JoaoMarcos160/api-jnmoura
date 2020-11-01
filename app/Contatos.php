<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contatos extends Model
{
    protected $fillable = [
        'id',
        'nome',
        'telefone',
        'endereco',
        'created_at',
        'updated_at',
    ];

    protected $table = 'contatos';
}
