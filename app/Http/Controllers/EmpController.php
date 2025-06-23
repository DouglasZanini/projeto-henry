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

    // Método para visualizar empregado (chamado via AJAX no JavaScript)
    public function show(Emp $empregado)
    {
        // Carregar o empregado com seus relacionamentos
        $empregado->load('departamento', 'gerente');
        
        // Preparar dados para retorno JSON
        $data = [
            'id' => $empregado->id,
            'primeiro_nome' => $empregado->primeiro_nome,
            'ultimo_nome' => $empregado->ultimo_nome,
            'userid' => $empregado->userid,
            'admissao' => $empregado->admissao,
            'funcao' => $empregado->funcao,
            'salario' => number_format($empregado->salario, 2, ',', '.'),
            'comissao' => $empregado->comissao ? number_format($empregado->comissao, 2, ',', '.') : null,
            'dept_id' => $empregado->dept_id,
            'dept_nome' => $empregado->departamento ? $empregado->departamento->nome : null,
            'gerente_id' => $empregado->gerente_id,
            'gerente_nome' => $empregado->gerente ? 
                $empregado->gerente->primeiro_nome . ' ' . $empregado->gerente->ultimo_nome : null,
            'obs' => $empregado->obs
        ];

        return response()->json($data);
    }

    // Método para buscar dados do empregado para edição (chamado via AJAX no JavaScript)
    public function edit(Emp $empregado)
    {
        // Carregar o empregado com seus relacionamentos
        $empregado->load('departamento', 'gerente');
        
        // Preparar dados para retorno JSON (mantendo valores originais para edição)
        $data = [
            'id' => $empregado->id,
            'primeiro_nome' => $empregado->primeiro_nome,
            'ultimo_nome' => $empregado->ultimo_nome,
            'userid' => $empregado->userid,
            'admissao' => $empregado->admissao, // Formato Y-m-d para input date
            'funcao' => $empregado->funcao,
            'salario' => $empregado->salario, // Valor numérico para input
            'comissao' => $empregado->comissao, // Valor numérico para input
            'dept_id' => $empregado->dept_id,
            'gerente_id' => $empregado->gerente_id,
            'obs' => $empregado->obs
        ];

        return response()->json($data);
    }

    public function update(Request $request, Emp $empregado)
    {
        $request->validate([
            'primeiro_nome' => 'required|string|max:100',
            'ultimo_nome' => 'required|string|max:100',
            'userid' => 'required|string|max:50|unique:emp,userid,' . $empregado->id,
            'admissao' => 'required|date',
            'funcao' => 'nullable|string|max:100',
            'salario' => 'required|numeric',
            'comissao' => 'nullable|numeric',
            'dept_id' => 'required|exists:dept,id',
            'gerente_id' => 'nullable|exists:emp,id',
            'obs' => 'nullable|string'
        ]);
    
        // Preparar dados para atualização, tratando campos que podem vir vazios
        $dados = $request->all();
        
        // Converter string vazia para null na comissão
        if (empty($dados['comissao']) || $dados['comissao'] === '') {
            $dados['comissao'] = null;
        }
        
        // Converter string vazia para null no gerente_id
        if (empty($dados['gerente_id']) || $dados['gerente_id'] === '') {
            $dados['gerente_id'] = null;
        }
        
        // Converter string vazia para null nas observações
        if (empty($dados['obs']) || $dados['obs'] === '') {
            $dados['obs'] = null;
        }
    
        $empregado->update($dados);
        
        // Verificar se é uma requisição AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Empregado atualizado com sucesso!'
            ]);
        }
    
        return redirect()->route('empregados.index')->with('success', 'Empregado atualizado com sucesso!');
    }

    public function destroy(Emp $empregado)
    {
        try {
            // Verificar se o empregado é gerente de outros empregados
            $subordinados = Emp::where('gerente_id', $empregado->id)->count();
            
            if ($subordinados > 0) {
                // Para requisições AJAX
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Não é possível excluir este empregado pois ele é gerente de outros funcionários. Remova ou transfira os subordinados primeiro.'
                    ], 400);
                }
    
                return redirect()->route('empregados.index')
                    ->with('error', 'Não é possível excluir este empregado pois ele é gerente de outros funcionários. Remova ou transfira os subordinados primeiro.');
            }
    
            $empregado->delete();
            
            // Para requisições AJAX
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Empregado excluído com sucesso!'
                ]);
            }
    
            return redirect()->route('empregados.index')->with('success', 'Empregado excluído com sucesso!');
            
        } catch (\Exception $e) {
            // Tratar especificamente erro de foreign key
            if (strpos($e->getMessage(), 'foreign key constraint') !== false) {
                $message = 'Não é possível excluir este empregado pois ele possui vínculos com outros registros no sistema.';
            } else {
                $message = 'Erro ao excluir empregado: ' . $e->getMessage();
            }
            
            // Para requisições AJAX
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }
    
            return redirect()->route('empregados.index')->with('error', $message);
        }
    }
}