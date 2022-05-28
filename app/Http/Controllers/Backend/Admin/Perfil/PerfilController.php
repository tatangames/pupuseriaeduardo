<?php

namespace App\Http\Controllers\Backend\Admin\Perfil;

use App\Http\Controllers\Controller;
use App\Jobs\SendNotiClienteJobs;
use App\Jobs\SendNotiPropietarioJobs;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function indexEditarPerfil(){
        $usuario = auth()->user();
        return view('backend.admin.perfil.vistaPerfil', compact('usuario'));
    }

    public function editarUsuario(Request $request){


        SendNotiPropietarioJobs::dispatch('titulo', 'mensaje', '4b56f913-ac71-4f10-a617-be38573cc7f8');


        return ['success' => 1];

        $regla = array(
            'password' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0];}

        $usuario = auth()->user();

        Usuarios::where('id', $usuario->id)
            ->update(['password' => bcrypt($request->password)]);

        return ['success' => 1];
    }


}
