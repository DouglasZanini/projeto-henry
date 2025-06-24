<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produto extends Model
{
    /**
     * Nome da tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'produto';
    
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
        'nome',
        'descricao_breve',
        'textolongo_id',
        'imagem_id',
        'preco_sugerido',
        'unidades',
    ];
    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array
     */
    protected $casts = [
        'preco_sugerido' => 'decimal:2',
        'unidades' => 'string', // Alterado de integer para string
    ];
    
    /**
     * Obtém o texto longo associado ao produto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function textoLongo(): BelongsTo
    {
        return $this->belongsTo(TextoLongo::class, 'textolongo_id');
    }
    
    /**
     * Obtém a imagem associada ao produto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function imagem(): BelongsTo
    {
        return $this->belongsTo(Imagem::class, 'imagem_id');
    }
    
    /**
     * Obtém os registros de inventário deste produto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventario(): HasMany
    {
        return $this->hasMany(Inventario::class, 'produto_id');
    }
    
    /**
     * Obtém os itens de pedido relacionados a este produto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itens(): HasMany
    {
        return $this->hasMany(Item::class, 'produto_id');
    }
    
    /**
     * Verifica se o produto tem texto longo.
     *
     * @return bool
     */
    public function hasTextoLongo(): bool
    {
        return !is_null($this->textolongo_id);
    }
    
    /**
     * Verifica se o produto tem imagem.
     *
     * @return bool
     */
    public function hasImagem(): bool
    {
        return !is_null($this->imagem_id);
    }
    
    /**
     * Obtém o estoque total deste produto em todas as localizações.
     *
     * @return int
     */
    public function getEstoqueTotalAttribute(): int
    {
        return $this->inventario()->sum('quantidade');
    }
    
    /**
     * Formata o preço sugerido para exibição.
     *
     * @return string
     */
    public function getPrecoFormatadoAttribute(): string
    {
        return 'R$ ' . number_format($this->preco_sugerido, 2, ',', '.');
    }
}