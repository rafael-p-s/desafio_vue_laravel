<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class ModelEmpresa extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'empresas';

    protected $fillable = [ 
        'razao_social',
        'nome_fantasia',
        'cnpj',
    ];

    // Empresa:Setor (N:N)
    public function setores(): BelongsToMany
    {
        return $this->belongsToMany(ModelSetor::class, 'empresa_setor', 'empresa_id', 'setor_id');
    }
}
