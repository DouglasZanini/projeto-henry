<?php
namespace App\Http\Controllers;

use App\Models\Cliente;

use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with(['regiao', 'vendedor'])->get();
        return view('modules.clientes.index', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'fone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:100',
            'pais' => 'nullable|string|max:100',
            'cep' => 'nullable|string|max:10',
            'credito' => 'nullable|numeric',
            'vendedor_id' => 'nullable|exists:vendedor,id',
            'regiao_id' => 'nullable|exists:regiao,id',
            'obs' => 'nullable|string',
        ]);

        Cliente::create($request->all());
        return redirect()->route('clientes.index')->with('success', 'Cliente cadastrado com sucesso!');
    }

    public function show($id)
    {
        return Cliente::with(['regiao', 'vendedor'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'fone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:100',
            'pais' => 'nullable|string|max:100',
            'cep' => 'nullable|string|max:10',
            'credito' => 'nullable|numeric',
            'vendedor_id' => 'nullable|exists:vendedor,id',
            'regiao_id' => 'nullable|exists:regiao,id',
            'obs' => 'nullable|string',
        ]);

        $cliente->update($request->all());
        return redirect()->route('clientes.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Cliente::destroy($id);
        return redirect()->route('clientes.index')->with('success', 'Cliente excluído com sucesso!');
    }
}