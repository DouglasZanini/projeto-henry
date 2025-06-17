<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    /**
     * Nome da tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'item';
    
    /**
     * Indica se o modelo deve ter timestamps automáticos.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Indica que o modelo não tem uma única chave primária auto-incrementável.
     *
     * @var bool
     */
    public $incrementing = false;
    
    /**
     * Define o tipo das chaves primárias.
     *
     * @var string
     */
    protected $keyType = 'array';
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'ord_id',
        'item_id',
        'produto_id',
        'preco',
        'quantidade',
        'quantidade_expedida',
    ];
    
    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array
     */
    protected $casts = [
        'preco' => 'decimal:2',
        'quantidade' => 'integer',
        'quantidade_expedida' => 'integer',
    ];
    
    /**
     * Obtém a chave primária para o modelo.
     *
     * @return array
     */
    public function getKey()
    {
        return [
            $this->getAttribute('ord_id'),
            $this->getAttribute('item_id'),
        ];
    }
    
    /**
     * Obtém o nome qualificado do campo da chave primária.
     *
     * @return string
     */
    public function getQualifiedKeyName()
    {
        return ['ord_id', 'item_id'];
    }
    
    /**
     * Obtém o pedido associado ao item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ordem(): BelongsTo
    {
        return $this->belongsTo(Ord::class, 'ord_id');
    }
    
    /**
     * Obtém o produto associado ao item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
    
    /**
     * Calcula o valor total do item (preço x quantidade).
     *
     * @return float
     */
    public function getValorTotalAttribute()
    {
        return $this->preco * $this->quantidade;
    }
    
    /**
     * Verifica se o item foi totalmente expedido.
     *
     * @return bool
     */
    public function isFullyShipped()
    {
        return $this->quantidade_expedida == $this->quantidade;
    }
}