<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Regiao;
use App\Models\Emp;
use App\Models\Ord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $clientes = Cliente::with(['regiao', 'vendedor'])->get();
        
        // Para requisições de atualização parcial da tabela
        if ($request->input('partial')) {
            return view('modules.clientes.partials.table', compact('clientes'));
        }
        
        // Carregar regiões para o dropdown
        $regioes = Regiao::all();
        $vendedores = Emp::where('funcao', 'VENDEDOR')->orWhere('funcao', 'GERENTE')->get();
        
        return view('modules.clientes.index', compact('clientes', 'regioes', 'vendedores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:50',
            'fone' => 'nullable|string|max:25',
            'endereco' => 'nullable|string|max:400',
            'cidade' => 'nullable|string|max:30',
            'estado' => 'nullable|string|max:20',
            'pais' => 'nullable|string|max:30',
            'cep' => 'nullable|string|max:75',
            'credito' => 'nullable|in:excelente,bom,ruim',
            'vendedor_id' => 'nullable|exists:emp,id',
            'regiao_id' => 'nullable|exists:regiao,id',
            'obs' => 'nullable|string|max:255',
        ]);
        
        // Converter strings vazias para null
        foreach ($validated as $key => $value) {
            if ($value === '') {
                $validated[$key] = null;
            }
        }
        
        DB::beginTransaction();
        
        try {
            // Obter o próximo ID disponível
            $nextId = DB::selectOne('SELECT COALESCE(MAX(id) + 1, 1) as next_id FROM cliente')->next_id;
            
            // Criar cliente com ID manual
            $cliente = new Cliente();
            $cliente->id = $nextId;
            $cliente->fill($validated);
            $cliente->save();
            
            DB::commit();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cliente cadastrado com sucesso!'
                ]);
            }
            
            return redirect()->route('clientes.index')->with('success', 'Cliente cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao cadastrar cliente: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('clientes.index')->with('error', 'Erro ao cadastrar cliente: ' . $e->getMessage());
        }
    }

    public function show(Cliente $cliente)
    {
        // Carregar relacionamentos para o modal de visualização
        $cliente->load(['regiao', 'vendedor']);
        
        return response()->json([
            'id' => $cliente->id,
            'nome' => $cliente->nome,
            'fone' => $cliente->fone,
            'endereco' => $cliente->endereco,
            'cidade' => $cliente->cidade,
            'estado' => $cliente->estado,
            'pais' => $cliente->pais,
            'cep' => $cliente->cep,
            'credito' => $cliente->credito,
            'vendedor_id' => $cliente->vendedor_id,
            'vendedor_nome' => $cliente->vendedor ? $cliente->vendedor->primeiro_nome . ' ' . $cliente->vendedor->ultimo_nome : null,
            'regiao_id' => $cliente->regiao_id,
            'regiao_nome' => $cliente->regiao->nome ?? null,
            'obs' => $cliente->obs,
            'endereco_completo' => $cliente->endereco_completo
        ]);
    }

    public function edit(Cliente $cliente)
    {
        return response()->json($cliente);
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:50',
            'fone' => 'nullable|string|max:25',
            'endereco' => 'nullable|string|max:400',
            'cidade' => 'nullable|string|max:30',
            'estado' => 'nullable|string|max:20',
            'pais' => 'nullable|string|max:30',
            'cep' => 'nullable|string|max:75',
            'credito' => 'nullable|in:excelente,bom,ruim',
            'vendedor_id' => 'nullable|exists:emp,id',
            'regiao_id' => 'nullable|exists:regiao,id',
            'obs' => 'nullable|string|max:255',
        ]);
        
        // Converter strings vazias para null
        foreach ($validated as $key => $value) {
            if ($value === '') {
                $validated[$key] = null;
            }
        }
        
        try {
            $cliente->update($validated);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cliente atualizado com sucesso!'
                ]);
            }
            
            return redirect()->route('clientes.index')->with('success', 'Cliente atualizado com sucesso!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao atualizar cliente: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('clientes.index')->with('error', 'Erro ao atualizar cliente: ' . $e->getMessage());
        }
    }

    public function destroy(Cliente $cliente)
    {
        try {
            // Verificar se o cliente possui pedidos
            $pedidosCount = Ord::where('cliente_id', $cliente->id)->count();
            
            if ($pedidosCount > 0) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Não é possível excluir este cliente pois ele possui pedidos vinculados.'
                    ], 400);
                }
                
                return redirect()->route('clientes.index')
                    ->with('error', 'Não é possível excluir este cliente pois ele possui pedidos vinculados.');
            }
            
            $cliente->delete();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cliente excluído com sucesso!'
                ]);
            }
            
            return redirect()->route('clientes.index')->with('success', 'Cliente excluído com sucesso!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao excluir cliente: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('clientes.index')->with('error', 'Erro ao excluir cliente: ' . $e->getMessage());
        }
    }
}