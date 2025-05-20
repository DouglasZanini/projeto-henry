<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regiao extends Model
{

    protected $table = 'regiao';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['nome'];

}
