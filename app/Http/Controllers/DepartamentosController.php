<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departamentos = Departamento::all();
        return view('modules.departamentos.index', compact('departamentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departamentos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:25',
            'regiao_id' => 'required|integer|exists:regiao,id',
            'total_salarios' => 'nullable|numeric',
        ]);

        // Verificar restrição única para nome e regiao_id
        $existingDept = Departamento::where('nome', $request->nome)
                           ->where('regiao_id', $request->regiao_id)
                           ->first();

        if ($existingDept) {
            return response()->json([
                'message' => 'Já existe um departamento com este nome nesta região.'
            ], 422);
        }

        // Encontrar o próximo ID disponível
        $maxId = Departamento::max('id') ?? 0;
        $nextId = $maxId + 1;

        // Criar o registro com ID explícito
        $dept = new Departamento();
        $dept->id = $nextId;
        $dept->nome = $request->nome;
        $dept->regiao_id = $request->regiao_id;
        $dept->total_salarios = $request->total_salarios;
        $dept->save();

        return response()->json([
            'message' => 'Departamento criado com sucesso!', 
            'data' => $dept
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $dept = Departamento::with('regiao')->findOrFail($id);
        return response()->json($dept);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dept = Departamento::with('regiao')->findOrFail($id);
        return response()->json(['message' => 'Form for editing the department', 'data' => $dept]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:25',
            'regiao_id' => 'required|integer|exists:regiao,id',
            'total_salarios' => 'nullable|numeric',
        ]);

        $dept = Departamento::findOrFail($id);

        // Verificar restrição única para nome e regiao_id se algum deles foi alterado
        if ($dept->nome !== $request->nome || $dept->regiao_id != $request->regiao_id) {
            $existingDept = Departamento::where('nome', $request->nome)
                               ->where('regiao_id', $request->regiao_id)
                               ->where('id', '!=', $id)
                               ->first();

            if ($existingDept) {
                return response()->json([
                    'message' => 'Já existe um departamento com este nome nesta região.'
                ], 422);
            }
        }

        $dept->nome = $request->nome;
        $dept->regiao_id = $request->regiao_id;
        $dept->total_salarios = $request->total_salarios;
        $dept->save();

        return response()->json(['message' => 'Departamento atualizado com sucesso!', 'data' => $dept]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $dept = Departamento::findOrFail($id);
        $dept->delete();

        return response()->json(['message' => 'Departamento excluído com sucesso!']);
    }
}