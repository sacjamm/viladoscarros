<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Venda extends Model
{
    use HasFactory;

    protected $table = 'vendas';

    protected $fillable = [
        'user_id', 'como_nos_conheceu', 'nomeCliente', 'cpfCliente',
        'dataVenda', 'valorVendaAvista', 'dataPagamentoFinanciamento',
        'veiculo', 'placa', 'valorTotalVeiculo', 'valorTotalFinanciamento',
        'financeira', 'status'
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function venda(): BelongsTo
    {
        return $this->belongsTo(Banco::class, 'financeira');
    }
}
