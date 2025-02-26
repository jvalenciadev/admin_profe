<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Comunicado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (is_null($this->user)) {
            abort(403, 'Lo siento !! ¡No estás autorizado a ver su perfil!');
        }

        $perfil = Admin::where('id', '=', $this->user->id)->first();
        $usuarios = Admin::orderBy('nombre')
        ->orderBy('apellidos')
        ->get();
        $comunicados = Comunicado::where('comun_estado','<>','eliminado')->get();
        return view('backend.pages.perfil.edit', compact('perfil','usuarios','comunicados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('comunicado.edit')) {
            abort(403, 'Lo siento !! ¡No estas autorizado a crear ningún comunicado !');
        }

        return view('backend.pages.comunicado.create');
    }

    /**
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     */
    public function update(Request $request, string $id)
    {


        $admin = Admin::find($id);


        // if ($request->hasFile('comun_imagen')) {
        //     // Eliminar el afiche actual si existe
        //     if ($comunicado->comun_imagen) {
        //         Storage::delete('public/comunicado/' . $comunicado->comun_imagen);
        //     }
        //     // Guardar el nuevo afiche
        //     $comunicadoImagePath = $request->file('comun_imagen')->store('public/comunicado');
        //     $comunicado->comun_imagen = basename($comunicadoImagePath);
        // }

       	$admin->genero = $request->genero??null;
        $admin->licenciatura = $request->licenciatura??null;
        $admin->fecha_nacimiento = $request->fecha_nacimiento??null;
        $admin->estado_civil = $request->estado_civil??null;
        $admin->celular = $request->celular??null;
        $admin->direccion = $request->direccion??null;
        $admin->facebook = $request->facebook??null;
        $admin->tiktok = $request->tiktok??null;        // Subir imagen
        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior si existe
            if ($admin->imagen && file_exists(storage_path('app/public/perfil/' . $admin->imagen))) {
                unlink(storage_path('app/public/perfil/' . $admin->imagen));
            }

            // Subir la nueva imagen
            $imagen = $request->file('imagen');
            $imagePath = $imagen->storeAs('public/perfil', $admin->id . '_perfil.' . $imagen->getClientOriginalExtension());
            $admin->imagen = basename($imagePath); // Guardar solo el nombre del archivo
        }

        // Subir currículum
        if ($request->hasFile('curriculum')) {
            // Eliminar el currículum anterior si existe
            if ($admin->curriculum && file_exists(storage_path('app/public/perfil/' . $admin->curriculum))) {
                unlink(storage_path('app/public/perfil/' . $admin->curriculum));
            }

            // Subir el nuevo currículum
            $pdf = $request->file('curriculum');
            $pdfPath = $pdf->storeAs('public/perfil', $admin->id .'_curriculum.' . $pdf->getClientOriginalExtension());
            $admin->curriculum = basename($pdfPath); // Guardar solo el nombre del archivo
        }
        $admin->save();

        return redirect()->route('admin.perfil.index')->with('success', 'Perfil actualizado exitosamente.');
    }

    /**
     */
    public function destroy(string $id)
    {
        $comunicado = Comunicado::find($id);

        if (!$comunicado) {
            abort(404, 'Comunicado no encontrado.');
        }
        $comunicado->comun_estado = 'eliminado';
        $comunicado->save();

        return redirect()->route('admin.comunicado.index')
            ->with('success', 'Comunicado eliminado exitosamente.');
    }

    public function estado(string $id)
    {
        $comunicado = Comunicado::find($id);
        if ($comunicado->comun_estado == 'activo') {
            $comunicado->comun_estado = 'inactivo';
        } else {
            $comunicado->comun_estado = 'activo';
        }
        $comunicado->save();

        return back()->with('success', 'Estado actualizado');
    }
}
