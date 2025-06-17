<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ord extends Model
{
    /**
     * Nome da tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'ord';
    
    /**
     * Chave primária da tabela.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    /**
     * Indica se o modelo deve ter timestamps automáticos.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'cliente_id',
        'data_ordenamento',
        'data_expedicao',
        'vendedor_id',
        'total',
        'tipo_pagamento',
        'ordem_cheia',
    ];
    
    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array
     */
    protected $casts = [
        'data_ordenamento' => 'date',
        'data_expedicao' => 'date',
        'total' => 'decimal:2',
    ];
    
    /**
     * Obtém o cliente associado ao pedido.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
    
    /**
     * Obtém o vendedor associado ao pedido.
     */
    public function vendedor()
    {
        return $this->belongsTo(Emp::class, 'vendedor_id');
    }
    
    /**
     * Obtém os itens associados ao pedido.
     */
    public function itens()
    {
        return $this->hasMany(Item::class, 'ord_id');
    }
    
    /**
     * Verifica se o pedido está completo.
     *
     * @return bool
     */
    public function isComplete()
    {
        return $this->ordem_cheia === 'Y';
    }
    
    /**
     * Verifica se o pagamento é em dinheiro.
     *
     * @return bool
     */
    public function isCashPayment()
    {
        return $this->tipo_pagamento === 'CASH';
    }
    
    /**
     * Verifica se o pagamento é com cartão de crédito.
     *
     * @return bool
     */
    public function isCreditPayment()
    {
        return $this->tipo_pagamento === 'CREDIT';
    }
}