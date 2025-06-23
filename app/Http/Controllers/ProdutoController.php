<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::all();
        return view('modules.produtos.index', compact('produtos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao_breve' => 'nullable|string|max:255',
            'textolongo_id' => 'nullable|integer',
            'imagem_id' => 'nullable|integer',
            'preco_sugerido' => 'nullable|numeric',
            'unidades' => 'nullable|integer',
        ]);

        DB::beginTransaction();

        try {
            // Obter o próximo ID disponível
            $nextId = DB::selectOne('SELECT COALESCE(MAX(id) + 1, 1) as next_id FROM produto')->next_id;

            // Preparar dados tratando campos vazios
            $dados = $request->all();

            // Converter strings vazias para null
            if (empty($dados['textolongo_id']) || $dados['textolongo_id'] === '') {
                $dados['textolongo_id'] = null;
            }
            if (empty($dados['imagem_id']) || $dados['imagem_id'] === '') {
                $dados['imagem_id'] = null;
            }
            if (empty($dados['preco_sugerido']) || $dados['preco_sugerido'] === '') {
                $dados['preco_sugerido'] = null;
            }
            if (empty($dados['unidades']) || $dados['unidades'] === '') {
                $dados['unidades'] = null;
            }

            // Criar produto com ID manual
            $produto = new Produto();
            $produto->id = $nextId;
            $produto->fill($dados);
            $produto->save();

            DB::commit();

            // Para requisições AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produto cadastrado com sucesso!'
                ]);
            }

            return redirect()->route('produtos.index')->with('success', 'Produto cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Para requisições AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao cadastrar produto: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('produtos.index')->with('error', 'Erro ao cadastrar produto: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $produto = Produto::findOrFail($id);

        $data = [
            'id' => $produto->id,
            'nome' => $produto->nome,
            'descricao_breve' => $produto->descricao_breve,
            'textolongo_id' => $produto->textolongo_id,
            'imagem_id' => $produto->imagem_id,
            'preco_sugerido' => $produto->preco_sugerido ? number_format($produto->preco_sugerido, 2, ',', '.') : null,
            'unidades' => $produto->unidades
        ];

        return response()->json($data);
    }

    public function edit($id)
    {
        $produto = Produto::findOrFail($id);

        $data = [
            'id' => $produto->id,
            'nome' => $produto->nome,
            'descricao_breve' => $produto->descricao_breve,
            'textolongo_id' => $produto->textolongo_id,
            'imagem_id' => $produto->imagem_id,
            'preco_sugerido' => $produto->preco_sugerido,
            'unidades' => $produto->unidades
        ];

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao_breve' => 'nullable|string|max:255',
            'textolongo_id' => 'nullable|integer',
            'imagem_id' => 'nullable|integer',
            'preco_sugerido' => 'nullable|numeric',
            'unidades' => 'nullable|integer',
        ]);

        try {
            $produto = Produto::findOrFail($id);

            // Preparar dados tratando campos vazios
            $dados = $request->all();

            // Converter strings vazias para null
            if (empty($dados['textolongo_id']) || $dados['textolongo_id'] === '') {
                $dados['textolongo_id'] = null;
            }
            if (empty($dados['imagem_id']) || $dados['imagem_id'] === '') {
                $dados['imagem_id'] = null;
            }
            if (empty($dados['preco_sugerido']) || $dados['preco_sugerido'] === '') {
                $dados['preco_sugerido'] = null;
            }
            if (empty($dados['unidades']) || $dados['unidades'] === '') {
                $dados['unidades'] = null;
            }

            $produto->update($dados);

            // Para requisições AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produto atualizado com sucesso!'
                ]);
            }

            return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
        } catch (\Exception $e) {
            // Para requisições AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao atualizar produto: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('produtos.index')->with('error', 'Erro ao atualizar produto: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $produto = Produto::findOrFail($id);

            // Verificar se há itens de venda vinculados (comentar se não tiver model Item)
            // $itensCount = $produto->itens()->count();
            // 
            // if ($itensCount > 0) {
            //     if (request()->expectsJson()) {
            //         return response()->json([
            //             'success' => false,
            //             'message' => 'Não é possível excluir este produto pois ele possui vendas vinculadas.'
            //         ], 400);
            //     }
            //     
            //     return redirect()->route('produtos.index')
            //         ->with('error', 'Não é possível excluir este produto pois ele possui vendas vinculadas.');
            // }

            $produto->delete();

            // Para requisições AJAX
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produto excluído com sucesso!'
                ]);
            }

            return redirect()->route('produtos.index')->with('success', 'Produto excluído com sucesso!');
        } catch (\Exception $e) {
            // Para requisições AJAX
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao excluir produto: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('produtos.index')
                ->with('error', 'Erro ao excluir produto: ' . $e->getMessage());
        }
    }
}
