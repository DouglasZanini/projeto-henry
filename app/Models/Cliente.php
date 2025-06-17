<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    /**
     * Nome da tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'cliente';
    
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
        'fone',
        'endereco',
        'cidade',
        'estado',
        'pais',
        'cep',
        'credito',
        'vendedor_id',
        'regiao_id',
        'obs',
    ];
    
    /**
     * Os valores possíveis para o atributo credito.
     * 
     * @var array
     */
    const CREDITO_TIPOS = ['excelente', 'bom', 'ruim'];
    
    /**
     * Obtém o vendedor associado ao cliente.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(Emp::class, 'vendedor_id');
    }
    
    /**
     * Obtém a região associada ao cliente.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function regiao(): BelongsTo
    {
        return $this->belongsTo(Regiao::class, 'regiao_id');
    }
    
    /**
     * Obtém os pedidos do cliente.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pedidos(): HasMany
    {
        return $this->hasMany(Ord::class, 'cliente_id');
    }
    
    /**
     * Verifica se o cliente tem crédito excelente.
     *
     * @return bool
     */
    public function hasExcellentCredit(): bool
    {
        return $this->credito === 'excelente';
    }
    
    /**
     * Verifica se o cliente tem bom crédito.
     *
     * @return bool
     */
    public function hasGoodCredit(): bool
    {
        return $this->credito === 'bom';
    }
    
    /**
     * Verifica se o cliente tem crédito ruim.
     *
     * @return bool
     */
    public function hasBadCredit(): bool
    {
        return $this->credito === 'ruim';
    }
    
    /**
     * Define o crédito do cliente.
     *
     * @param string $value
     * @return void
     * @throws \InvalidArgumentException
     */
    public function setCreditoAttribute(string $value): void
    {
        if (!in_array($value, self::CREDITO_TIPOS)) {
            throw new \InvalidArgumentException("Tipo de crédito inválido. Valores permitidos: " . implode(', ', self::CREDITO_TIPOS));
        }
        
        $this->attributes['credito'] = $value;
    }
    
    /**
     * Obtém o endereço completo do cliente.
     *
     * @return string
     */
    public function getEnderecoCompletoAttribute(): string
    {
        $endereco = $this->endereco;
        
        if ($this->cidade) {
            $endereco .= ', ' . $this->cidade;
        }
        
        if ($this->estado) {
            $endereco .= ', ' . $this->estado;
        }
        
        if ($this->pais) {
            $endereco .= ', ' . $this->pais;
        }
        
        if ($this->cep) {
            $endereco .= ' - ' . $this->cep;
        }
        
        return $endereco;
    }
}