<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmpresaController extends Controller
{
    //
    
    public function index(Request $request)
    {
        // Retorna a lista de posts
         
        $data['empresas'] = Empresa::when($request->input_busca_empresas, function($query, $value){
            $query->where('nome_empresa', 'like', "%".$value."%");
            $query->orWhere('codigo_empresa', $value);
        })
        ->with(['endereco', 'regime', 'moeda.base', 'moeda.alternativa', 'moeda.cambio', 'tipo', 'grupo'])
        ->get();
    
        return Inertia::render('Admin/Empresas/Index', $data);
    }
        

    public function show($id)
    {
        $data['empresa'] = Empresa::with(['contactos.contacto', 'documentos.documento', 'endereco.pais', 'endereco.provincia', 'endereco.municipio', 'endereco.comuna', 'regime', 'moeda.base', 'moeda.alternativa', 'moeda.cambio', 'tipo', 'grupo'])->findOrFail($id);
        
        return Inertia::render('Admin/Empresas/Show', $data);
    }
}
