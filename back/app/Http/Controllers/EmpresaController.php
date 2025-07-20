<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmpresaRequest;
use App\Models\ModelEmpresa;
use App\Models\ModelSetor;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    // Get all empresas
    public function empresasOnly()
    {
        try {
            
            $empresa = ModelEmpresa::orderBy('id')->get();
            return response()->json($empresa, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao exibir empresas.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    // Create a new empresa
    public function store(CreateEmpresaRequest $req)
    {
        try {
            $empresa = ModelEmpresa::create([
                "razao_social" => $req->razao_social,
                "nome_fantasia" => $req->nome_fantasia,
                "cnpj" => $req->cnpj,
            ]);

            return response()->json([
                'message' => 'Empresa criada com sucesso!',
                'empresa' => $empresa
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao criar empresa.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    // Update empresa using ID
    public function update(Request $req, ModelEmpresa $empresa)
    {
        $req->validate([
            'razao_social' => 'required|string|max:255',
            'nome_fantasia' => 'nullable|string|max:255',
            'cnpj' => [
                'required',
                'string',
                'size:14',
                'unique:empresas,cnpj,' . $empresa->id
            ],
        ]);

        try {
            $empresa->update([
                'razao_social' => $req->razao_social,
                'nome_fantasia' => $req->nome_fantasia,
                'cnpj' => $req->cnpj,
            ]);

            return response()->json([
                'message' => 'Empresa atualizada com sucesso!',
                'empresa' => $empresa
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao atualizar empresa.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    // Delete empresa using ID
    public function delete(ModelEmpresa $empresa)
    {
        try {
            $empresa->setores()->detach(); // Desvincula setores antes de excluir, na tabela empresa_setores
            $empresa->delete();
            return response()->json([
                'message' => 'Empresa excluída com sucesso!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao excluir empresa.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    //////////////////////////////////////////////////////////////////

    // This area \/ is only to EMPRESA + SETOR

    // Get all Empresa + Setor
    public function listarEmpresaSetores()
    {
        try {
            $empresas = ModelEmpresa::with('setores')->get();

            $resultado = $empresas->map(function ($empresa) {
                return [
                    'empresa' => [
                        'id' => $empresa->id,
                        'razao_social' => $empresa->razao_social,
                        'nome_fantasia' => $empresa->nome_fantasia,
                        'cnpj' => $empresa->cnpj,
                    ],
                    'setores' => $empresa->setores->map(function ($setor) {
                        return [
                            'id' => $setor->id,
                            'descricao' => $setor->descricao,
                        ];
                    }),
                ];
            });

            return response()->json($resultado, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar empresas com setores vinculados.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function empresasComSetores()
    {
        $empresas = ModelEmpresa::with('setores')->get();

        $vinculos = [];

        foreach ($empresas as $empresa) {
            foreach ($empresa->setores as $setor) {
                $vinculos[] = [
                    'empresa' => $empresa,
                    'setor' => $setor,
                    'pivot_id' => $setor->pivot->id ?? null,
                ];
            }
        }

        return response()->json($vinculos, 200);
    }


    // Chekc if setor are available for empresa
    public function setoresDisponiveis(ModelEmpresa $empresa)
    {
        try {
            $setoresDisponiveis = ModelSetor::whereDoesntHave('empresas', function ($query) use ($empresa) {
                $query->where('empresa_id', $empresa->id);
            })->get();

            return response()->json($setoresDisponiveis, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao exibir setores disponíveis para esta empresa.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    // Add setores to EMPRESA + SETOR
    public function vincularSetores(Request $req, ModelEmpresa $empresa)
    {
        $req->validate([
            'setores' => 'required|array',
            'setores.*' => 'exists:setor,id',
        ]);

        try {
            $empresa->setores()->attach($req->setores);
            return response()->json([
                'message' => 'Setores vinculados com sucesso!',
                'empresa' => $empresa->load('setores')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao vincular setores.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    // Remove setor from EMPRESA + SETOR
    public function desvincularSetor(ModelEmpresa $empresa, $setorId)
    {
        try {
            if (!$empresa->setores()->where('setor_id', $setorId)->exists()) {
                return response()->json([
                    'message' => 'Setor não vinculado a esta empresa.'
                ], 404);
            }

            $empresa->setores()->detach($setorId);

            return response()->json([
                'message' => 'Setor desvinculado da empresa com sucesso!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao desvincular setor.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
