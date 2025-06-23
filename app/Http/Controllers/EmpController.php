<?php
namespace App\Http\Controllers;

use App\Models\Emp;
use App\Models\Departamento;
use Illuminate\Http\Request;

class EmpController extends Controller
{
    public function index()
    {
        $empregados = Emp::with('departamento', 'gerente')->get();
        $departamentos = Departamento::all();
        return view('modules.empregados.index', compact('empregados', 'departamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'primeiro_nome' => 'required|string|max:100',
            'ultimo_nome' => 'required|string|max:100',
            'userid' => 'required|string|max:50|unique:emp,userid',
            'admissao' => 'required|date',
            'funcao' => 'nullable|string|max:100',
            'salario' => 'required|numeric',
            'comissao' => 'nullable|numeric',
            'dept_id' => 'required|exists:dept,id'
        ]);

        Emp::create($request->all());
        return redirect()->route('empregados.index')->with('success', 'Empregado cadastrado com sucesso!');
    }

    public function update(Request $request, Emp $empregados)
    {
        $request->validate([
            'primeiro_nome' => 'required|string|max:100',
            'ultimo_nome' => 'required|string|max:100',
            'userid' => 'required|string|max:50|unique:emp,userid,' . $empregados->id,
            'admissao' => 'required|date',
            'funcao' => 'nullable|string|max:100',
            'salario' => 'required|numeric',
            'comissao' => 'nullable|numeric',
            'dept_id' => 'required|exists:dept,id'
        ]);

        $empregados->update($request->all());
        return redirect()->route('empregados.index')->with('success', 'Empregado atualizado com sucesso!');
    }

    public function destroy(Emp $empregados)
    {
        $empregados->delete();
        return redirect()->route('empregados.index')->with('success', 'Empregado excluído com sucesso!');
    }
}
