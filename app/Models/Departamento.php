<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'dept';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['nome', 'regiao_id', 'total_salarios'];

    public function regiao()
    {
        return $this->belongsTo(Regiao::class, 'regiao_id');
    }
}