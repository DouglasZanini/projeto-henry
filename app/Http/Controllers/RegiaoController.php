<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\Regiao;

use Illuminate\Http\Request;

class RegiaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $regioes = Regiao::all();

        return view('modules.regiao.index', compact('regioes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('regiao.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255'
        ]);
    
        DB::beginTransaction();
    
        try {
            // Obter o próximo ID disponível
            $nextId = DB::selectOne('SELECT COALESCE(MAX(id) + 1, 1) as next_id FROM regiao')->next_id;
            
            // Criar nova região
            $regiao = new Regiao();
            $regiao->id = $nextId; // Definir o ID manualmente
            $regiao->nome = $request->nome;
            $regiao->save();
    
            DB::commit();
    
            return redirect()->route('regiao.index')->with('success', 'Região criada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao criar região: ' . $e->getMessage());
    
            return redirect()->route('regiao.index')->with('error', 'Erro ao criar região: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $regiao = Regiao::findOrFail($id);

        return view('regiao.show', compact('regiao'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $regiao = Regiao::findOrFail($id);

        return view('regiao.edit', compact('regiao'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255'
        ]);

        $regiao = Regiao::findOrFail($id);
        $regiao->update($request->all());

        return redirect()->route('regiao.index')->with('success', 'Região atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $regiao = Regiao::findOrFail($id);
        $regiao->delete();

        return redirect()->route('regiao.index')->with('success', 'Região excluída com sucesso!');
    }
}
