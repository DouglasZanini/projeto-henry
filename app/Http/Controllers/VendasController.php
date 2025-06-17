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
    //verificar tudo kkk
    public function index()
    {

        return view('modules.vendas.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:cliente,id',
            'vendedor_id' => 'required|exists:vendedor,id',
            'produtos' => 'required|array',
            'produtos.*.produto_id' => 'required|exists:produto,id',
            'produtos.*.quantidade' => 'required|integer|min:1',
            'produtos.*.preco' => 'required|numeric|min:0',
            'tipo_pagamento' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();

        try {
            // Criar nova ordem
            $ord = new Ord();
            $ord->cliente_id = $request->cliente_id;
            $ord->vendedor_id = $request->vendedor_id;
            $ord->data_ordenamento = Carbon::now()->format('Y-m-d');
            $ord->data_expedicao = null;
            $ord->tipo_pagamento = $request->tipo_pagamento ?? null;

            // Calcula total somando preço * quantidade
            $total = 0;
            foreach ($request->produtos as $p) {
                $total += $p['preco'] * $p['quantidade'];
            }
            $ord->total = $total;
            $ord->ordem_cheia = 'Y';

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
                    'quantidade_expedida' => null,
                ]);
                $item_id++;
            }

            DB::commit();

            return redirect()->route('vendas.index')->with('success', 'Venda registrada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            // Opcional: Log::error($e->getMessage());
            return redirect()->route('vendas.index')->with('error', 'Erro ao registrar venda: ' . $e->getMessage());
        }
    }
}
