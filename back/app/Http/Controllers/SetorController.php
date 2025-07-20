<?php

namespace App\Http\Controllers;

use App\Models\ModelSetor;
use App\Http\Requests\CreateSetorRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SetorController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function setorOnly()
    {
        try {
            $setores = ModelSetor::orderBy('id')->get();
            return response()->json($setores, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao exibir setores.',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    // Create a new setor
    public function store()
    {
        try {
            $setor = ModelSetor::create([
                'descricao' => request('descricao'),
            ]);

            return response()->json([
                'message' => 'Setor criado com sucesso!',
                'setor' => $setor
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao criar setor.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $req, ModelSetor $setor)
    {
        $req->validate([
            'descricao' => 'required|string|max:255',
        ]);

        try {
            $setor->update([
                'descricao' => $req->descricao,
            ]);
            return response()->json([
                'message' => 'Setor atualizada com sucesso!',
                'empresa' => $setor
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao atualizar setor.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(ModelSetor $setor) {
        try {
            $setor->empresas()->detach(); // Desvincula empresas do setor antes;
            $setor->delete();

            return response()->json([
                'message' => 'Setor excluído com sucesso!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao desvincular setor.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    ///////////////////////////////////////////////////////

    // This area is only for SETOR + EMPRESA

    // Will check all Setor with Empresas
    public function setorEmpresa()
    {
        try {
            $setores = ModelSetor::with('empresas')->get();
            return response()->json($setores, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao exibir setores com empresas.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
