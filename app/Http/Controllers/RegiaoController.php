<?php

namespace App\Http\Controllers;

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

        return response()->json($regioes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['message' => 'Form for creating a new region']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:50|unique:regiao,nome'
        ]);

        // Encontrar o próximo ID disponível
        $maxId = Regiao::max('id') ?? 0;
        $nextId = $maxId + 1;

        // Criar o registro com ID explícito
        $regiao = new Regiao();
        $regiao->id = $nextId;
        $regiao->nome = $request->nome;
        $regiao->save();

        return response()->json([
            'message' => 'Região criada com sucesso!', 
            'data' => $regiao
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $regiao = Regiao::findOrFail($id);

        return response()->json($regiao);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $regiao = Regiao::findOrFail($id);

        return response()->json(['message' => 'Form for editing the region', 'data' => $regiao]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255'
        ]);

        $regiao = Regiao::findOrFail($id);
        $regiao->update($request->all());

        return response()->json(['message' => 'Região atualizada com sucesso!', 'data' => $regiao]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $regiao = Regiao::findOrFail($id);
        $regiao->delete();

        return response()->json(['message' => 'Região excluída com sucesso!']);
    }
}
