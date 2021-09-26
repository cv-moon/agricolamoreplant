<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::select(
            'id',
            'nombre',
            'descripcion'
        )
            ->orderBy('nombre', 'asc')
            ->get();
        return $categorias;
    }

    public function store(Request $request)
    {
        $categoria = new Categoria();
        $categoria->nombre = mb_strtoupper(trim($request->nombre));
        $categoria->descripcion = mb_strtoupper(trim($request->descripcion));
        $categoria->save();
    }

    public function update(Request $request)
    {
        $categoria = Categoria::findOrFail($request->id);
        $categoria->nombre = mb_strtoupper(trim($request->nombre));
        $categoria->descripcion = mb_strtoupper(trim($request->descripcion));
        $categoria->save();
    }

    public function detail(Request $request)
    {
        $categoria = Categoria::select(
            'id',
            'nombre',
            'descripcion'
        )
            ->where('id', $request->id)
            ->first();
        return $categoria;
    }

    public function validateName(Request $request)
    {
        $nombre = Categoria::select('nombre')
            ->where('nombre', 'like', '%' . mb_strtoupper(trim($request->q)) . '%')
            ->first();
        return $nombre;
    }
}
