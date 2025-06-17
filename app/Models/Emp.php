<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Emp extends Model
{
    /**
     * Nome da tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'emp';
    
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
        'ultimo_nome',
        'primeiro_nome',
        'userid',
        'admissao',
        'obs',
        'gerente_id',
        'funcao',
        'dept_id',
        'salario',
        'comissao',
    ];
    
    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array
     */
    protected $casts = [
        'admissao' => 'date',
        'salario' => 'decimal:2',
        'comissao' => 'decimal:2',
    ];
    
    /**
     * Os valores possíveis para o atributo comissão.
     * 
     * @var array
     */
    const COMISSAO_VALORES = [10.00, 12.50, 15.00, 17.50, 20.00];
    
    /**
     * Obtém o departamento do empregado.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'dept_id');
    }
    
    /**
     * Obtém o gerente do empregado.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gerente(): BelongsTo
    {
        return $this->belongsTo(Emp::class, 'gerente_id');
    }
    
    /**
     * Obtém os subordinados do empregado (quando ele é gerente).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subordinados(): HasMany
    {
        return $this->hasMany(Emp::class, 'gerente_id');
    }
    
    /**
     * Obtém a função do empregado.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Funcao::class, 'funcao', 'funcao');
    }
    
    /**
     * Obtém os depósitos gerenciados pelo empregado.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function depositosGerenciados(): HasMany
    {
        return $this->hasMany(Deposito::class, 'gerente_id');
    }
    
    /**
     * Obtém os pedidos (ordens) em que o empregado atuou como vendedor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pedidos(): HasMany
    {
        return $this->hasMany(Ord::class, 'vendedor_id');
    }
    
    /**
     * Obtém os clientes atendidos por este empregado (como vendedor).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class, 'vendedor_id');
    }
    
    /**
     * Define a comissão do empregado.
     *
     * @param float $value
     * @return void
     * @throws \InvalidArgumentException
     */
    public function setComissaoAttribute($value): void
    {
        if (!in_array((float)$value, self::COMISSAO_VALORES)) {
            throw new \InvalidArgumentException("Valor de comissão inválido. Valores permitidos: " . implode(', ', self::COMISSAO_VALORES));
        }
        
        $this->attributes['comissao'] = $value;
    }
    
    /**
     * Obtém o nome completo do empregado.
     *
     * @return string
     */
    public function getNomeCompletoAttribute(): string
    {
        return $this->primeiro_nome . ' ' . $this->ultimo_nome;
    }
    
    /**
     * Verifica se o empregado é gerente de alguém.
     *
     * @return bool
     */
    public function isGerente(): bool
    {
        return $this->subordinados()->exists();
    }
    
    /**
     * Calcula o tempo de serviço do empregado em anos.
     *
     * @return int
     */
    public function getTempoServicoAttribute(): int
    {
        return $this->admissao ? now()->diffInYears($this->admissao) : 0;
    }
    
    /**
     * Calcula o salário anual do empregado (salário mensal * 12).
     *
     * @return float
     */
    public function getSalarioAnualAttribute(): float
    {
        return $this->salario * 12;
    }
}