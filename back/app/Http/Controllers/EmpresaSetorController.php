<?php

namespace App\Http\Controllers;

use App\Models\Empresa_Setor;
use App\Models\ModelSetor;
use App\Http\Requests\CreateSetorRequest;
use Illuminate\Http\Request;

class EmpresaSetorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function setorOnly()
    {
        try {
            $setores = ModelSetor::all();
            return response()->json($setores, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao exibir setores.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

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

    /**
     * Show the form for creating a new resource.
     */
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

    /**
     * Update the specified resource in storage.
     */
    /* public function update(Request $request, Empresa_Setor $empresa_Setor)
    {
        //
    } */

    /**
     * Remove the specified resource from storage.
     */
   /*  public function delete(Empresa_Setor $empresa_Setor)
    {
        //
    } */
}
