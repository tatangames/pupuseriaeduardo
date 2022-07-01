<?php

namespace App\Http\Controllers\Backend\Admin\Eventos;

use App\Http\Controllers\Controller;
use App\Models\BloquesEventos;
use App\Models\BloqueSlider;
use App\Models\BloquesRecords;
use App\Models\EventoImagenes;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EventosController extends Controller
{
    public function indexEventos(){
        return view('backend.admin.eventos.vistabloqueEventos');
    }

    // tabla
    public function tablaEventos(){
        $eventos = BloquesEventos::orderBy('posicion')->get();

        foreach ($eventos as $ee){
            $ee->fecha = date("d-m-Y", strtotime($ee->fecha));
        }

        return view('backend.admin.eventos.tablabloqueEventos', compact('eventos'));
    }

    public function nuevoEvento(Request $request){

        $regla = array(
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        $cadena = Str::random(15);
        $tiempo = microtime();
        $union = $cadena.$tiempo;
        $nombre = str_replace(' ', '_', $union);

        $extension = '.'.$request->imagen->getClientOriginalExtension();
        $nombreFoto = $nombre.strtolower($extension);
        $avatar = $request->file('imagen');
        $upload = Storage::disk('imagenes')->put($nombreFoto, \File::get($avatar));

        if($upload){

            if($info = BloquesEventos::orderBy('posicion', 'DESC')->first()){
                $suma = $info->posicion + 1;
            }else{
                $suma = 1;
            }


            $ca = new BloquesEventos();
            $ca->nombre = $request->nombre;
            $ca->imagen = $nombreFoto;
            $ca->activo = 1;
            $ca->fecha = $request->fecha;
            $ca->posicion = $suma;

            if($ca->save()){
                return ['success' => 1];
            }else{
                return ['success' => 2];
            }
        }else{
            return ['success' => 2];
        }

    }

    public function informacionEvento(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        if($evento = BloquesEventos::where('id', $request->id)->first()){

            return ['success' => 1, 'evento' => $evento];
        }else{
            return ['success' => 2];
        }
    }

    public function editarEvento(Request $request){

        $rules = array(
            'id' => 'required',
            'nombre' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        if($info = BloquesEventos::where('id', $request->id)->first()){

            if($request->hasFile('imagen')){

                $cadena = Str::random(15);
                $tiempo = microtime();
                $union = $cadena.$tiempo;
                $nombre = str_replace(' ', '_', $union);

                $extension = '.'.$request->imagen->getClientOriginalExtension();
                $nombreFoto = $nombre.strtolower($extension);
                $avatar = $request->file('imagen');
                $upload = Storage::disk('imagenes')->put($nombreFoto, \File::get($avatar));

                if($upload){
                    $imagenOld = $info->imagen;

                    BloquesEventos::where('id', $request->id)->update([
                        'nombre' => $request->nombre,
                        'fecha' => $request->fecha,
                        'imagen' => $nombreFoto,
                        'activo' => $request->cbactivo
                    ]);

                    if(Storage::disk('imagenes')->exists($imagenOld)){
                        Storage::disk('imagenes')->delete($imagenOld);
                    }

                    return ['success' => 1];

                }else{
                    return ['success' => 2];
                }
            }else{
                // solo guardar datos

                BloquesEventos::where('id', $request->id)->update([
                    'nombre' => $request->nombre,
                    'fecha' => $request->fecha,
                    'activo' => $request->cbactivo
                ]);

                return ['success' => 1];
            }

        }else{
            return ['success' => 2];
        }
    }

    public function ordenarEvento(Request $request){

        $tasks = BloquesEventos::all();

        foreach ($tasks as $task) {
            $id = $task->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $task->update(['posicion' => $order['posicion']]);
                }
            }
        }
        return ['success' => 1];
    }

    public function borrarEvento(Request $request){
        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        if(BloquesEventos::where('id', $request->id)->first()){

            // borrar imagenes
            $eve = EventoImagenes::where('evento_id', $request->id)->get();

            foreach ($eve as $ee){
                if(Storage::disk('imagenes')->exists($ee->imagen)){
                    Storage::disk('imagenes')->delete($ee->imagen);
                }
            }

            EventoImagenes::where('evento_id', $request->id)->delete();
            BloquesEventos::where('id', $request->id)->delete();
            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    // **** IMAGENES *****


    public function indexImagenes($id){
        return view('backend.admin.eventos.imagenes.vistaImagenes', compact('id'));
    }

    // tabla
    public function tablaImagenes($id){
        $eventos = EventoImagenes::where('evento_id', $id)->orderBy('posicion')->get();

        return view('backend.admin.eventos.imagenes.tablaImagenes', compact('eventos'));
    }


    public function nuevoEventoImagen(Request $request){

        $regla = array(
            'id' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        $cadena = Str::random(15);
        $tiempo = microtime();
        $union = $cadena.$tiempo;
        $nombre = str_replace(' ', '_', $union);

        $extension = '.'.$request->imagen->getClientOriginalExtension();
        $nombreFoto = $nombre.strtolower($extension);
        $avatar = $request->file('imagen');
        $upload = Storage::disk('imagenes')->put($nombreFoto, \File::get($avatar));

        if($upload){


            if($info = EventoImagenes::orderBy('posicion', 'DESC')->first()){
                $suma = $info->posicion + 1;
            }else{
                $suma = 1;
            }

            $ca = new EventoImagenes();
            $ca->evento_id = $request->id;
            $ca->imagen = $nombreFoto;
            $ca->posicion = $suma;

            if($ca->save()){
                return ['success' => 1];
            }else{
                return ['success' => 2];
            }
        }else{
            return ['success' => 2];
        }
    }

    public function borrarEventoImagen(Request $request){
        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        if($info = EventoImagenes::where('id', $request->id)->first()){

            // borrar imagenes
            if(Storage::disk('imagenes')->exists($info->imagen)){
                Storage::disk('imagenes')->delete($info->imagen);
            }

            EventoImagenes::where('id', $request->id)->delete();
            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function ordenarEventoImagen(Request $request){

        $tasks = EventoImagenes::all();

        foreach ($tasks as $task) {
            $id = $task->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $task->update(['posicion' => $order['posicion']]);
                }
            }
        }
        return ['success' => 1];
    }



    //************* BLOQUE SLIDER ******************//

    public function indexSliders(){

        $productos = Producto::where('activo', 1)->orderBy('nombre')->get();

        return view('backend.admin.slider.vistaSlider', compact('productos'));
    }

    public function tablaSliders(){
        $slider = BloqueSlider::orderBy('posicion')->get();

        foreach ($slider as $ss){

            if($info = Producto::where('id', $ss->id_producto)->first()){
                $ss->producto = $info->nombre;
            }
        }

        return view('backend.admin.slider.tablaSlider', compact('slider'));
    }

    public function nuevoSliders(Request $request){

        if($request->file('imagen')){

            $cadena = Str::random(15);
            $tiempo = microtime();
            $union = $cadena.$tiempo;
            $nombre = str_replace(' ', '_', $union);

            $extension = '.'.$request->imagen->getClientOriginalExtension();
            $nombreFoto = $nombre.strtolower($extension);
            $avatar = $request->file('imagen');
            $upload = Storage::disk('imagenes')->put($nombreFoto, \File::get($avatar));

            if($upload){

                if($info = BloqueSlider::orderBy('posicion', 'DESC')->first()){
                    $suma = $info->posicion + 1;
                }else{
                    $suma = 1;
                }

                $ca = new BloqueSlider();
                $ca->descripcion = $request->nombre;
                $ca->imagen = $nombreFoto;
                $ca->id_producto = $request->producto;
                $ca->posicion = $suma;

                if($ca->save()){
                    return ['success' => 1];
                }else{
                    return ['success' => 2];
                }
            }else{
                return ['success' => 2];
            }

        }else {
            return ['success' => 2];
        }
    }

    public function ordenarSliders(Request $request){
        $tasks = BloqueSlider::all();

        foreach ($tasks as $task) {
            $id = $task->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $task->update(['posicion' => $order['posicion']]);
                }
            }
        }
        return ['success' => 1];
    }

    public function borrarSliders(Request $request){
        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        if($info = BloqueSlider::where('id', $request->id)->first()){

            if(Storage::disk('imagenes')->exists($info->imagen)){
                Storage::disk('imagenes')->delete($info->imagen);
            }

            BloqueSlider::where('id', $request->id)->delete();
            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function informacionSlider(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        if($bloque = BloqueSlider::where('id', $request->id)->first()){

            $producto = Producto::where('activo', 1)->orderBy('nombre')->get();

            return ['success' => 1, 'slider' => $bloque, 'producto' => $producto,
                'idproducto' => $bloque->id_producto];
        }else{
            return ['success' => 2];
        }
    }

    public function editarSlider(Request $request){

        if($info = BloqueSlider::where('id', $request->id)->first()){

            if($request->hasFile('imagen')){

                $cadena = Str::random(15);
                $tiempo = microtime();
                $union = $cadena.$tiempo;
                $nombre = str_replace(' ', '_', $union);

                $extension = '.'.$request->imagen->getClientOriginalExtension();
                $nombreFoto = $nombre.strtolower($extension);
                $avatar = $request->file('imagen');
                $upload = Storage::disk('imagenes')->put($nombreFoto, \File::get($avatar));

                if($upload){
                    $imagenOld = $info->imagen;

                    BloqueSlider::where('id', $request->id)->update([
                        'descripcion' => $request->nombre,
                        'imagen' => $nombreFoto,
                        'id_producto' => $request->producto
                    ]);

                    if(Storage::disk('imagenes')->exists($imagenOld)){
                        Storage::disk('imagenes')->delete($imagenOld);
                    }

                    return ['success' => 1];

                }else{
                    return ['success' => 2];
                }
            }else {

                BloqueSlider::where('id', $request->id)->update([
                    'descripcion' => $request->nombre,
                    'id_producto' => $request->producto
                ]);
            }

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

}
