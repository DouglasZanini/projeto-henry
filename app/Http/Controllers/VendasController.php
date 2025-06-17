<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ord;
use App\Models\Item;
use App\Models\Cliente;
use App\Models\Emp;
use App\Models\Produto;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VendasController extends Controller
{

    public function index()
    {
        // Carregar vendas com relacionamentos para a listagem principal
        $vendas = Ord::with(['cliente', 'vendedor'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('modules.vendas.index', compact('vendas'));
    }

    public function show($id)
    {
        // Carregar detalhes específicos de uma venda para a visualização
        $venda = Ord::with(['cliente', 'vendedor', 'itens.produto'])
            ->findOrFail($id);
            
        return response()->json($venda);
    }
    
    public function create()
    {
        $clientes = Cliente::orderBy('nome')->get();
        $vendedores = Emp::whereNotNull('comissao')->orderBy('ultimo_nome')->get(); // Empregados com comissão são vendedores
        $produtos = Produto::orderBy('nome')->get();

        return view('modules.vendas.create', compact('clientes', 'vendedores', 'produtos'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:cliente,id',
            'vendedor_id' => 'required|exists:emp,id',
            'produtos' => 'required|array',
            'produtos.*.produto_id' => 'required|exists:produto,id',
            'produtos.*.quantidade' => 'required|integer|min:1',
            'produtos.*.preco' => 'required|numeric|min:0',
            'tipo_pagamento' => 'required|in:CASH,CREDIT',
        ]);
    
        DB::beginTransaction();
    
        try {
            // Obter o próximo ID disponível
            $nextId = DB::selectOne('SELECT COALESCE(MAX(id) + 1, 1) as next_id FROM ord')->next_id;
            
            // Criar nova ordem
            $ord = new Ord();
            $ord->id = $nextId; // Definir o ID manualmente
            $ord->cliente_id = $request->cliente_id;
            $ord->vendedor_id = $request->vendedor_id;
            $ord->data_ordenamento = Carbon::now()->format('Y-m-d');
            $ord->data_expedicao = null;
            $ord->tipo_pagamento = $request->tipo_pagamento;
    
            // Calcula total somando preço * quantidade
            $total = 0;
            foreach ($request->produtos as $p) {
                $total += $p['preco'] * $p['quantidade'];
            }
            $ord->total = $total;
            $ord->ordem_cheia = 'N'; // Inicialmente marcada como não completa
    
            $ord->save();
    
            // Inserir itens, resetando item_id para cada ordem
            $item_id = 1;
            foreach ($request->produtos as $p) {
                Item::create([
                    'ord_id' => $ord->id,
                    'item_id' => $item_id,
                    'produto_id' => $p['produto_id'],
                    'preco' => $p['preco'],
                    'quantidade' => $p['quantidade'],
                    'quantidade_expedida' => 0,
                ]);
                $item_id++;
            }
    
            DB::commit();
    
            // Verificar se é uma requisição AJAX/Axios
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Venda registrada com sucesso!',
                    'data' => [
                        'order_id' => $ord->id,
                        'total' => number_format($total, 2, ',', '.'),
                    ],
                    'redirect' => route('vendas.index')
                ]);
            }
    
            return redirect()->route('vendas.index')->with('success', 'Venda registrada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao registrar venda: ' . $e->getMessage());
    
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao registrar venda: ' . $e->getMessage()
                ], 500);
            }
    
            return redirect()->route('vendas.index')->with('error', 'Erro ao registrar venda: ' . $e->getMessage());
        }
    }
}