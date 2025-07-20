<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ModelSetor extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'setor';

    protected $fillable = [
        'descricao',
    ];

    public function empresas(): BelongsToMany
    {
        return $this->belongsToMany(ModelEmpresa::class, 'empresa_setor', 'setor_id', 'empresa_id');
    }
}
