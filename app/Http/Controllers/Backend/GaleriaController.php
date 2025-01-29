<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Galeria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Programa;
use App\Models\Sede;


class GaleriaController extends Controller
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
        if (is_null($this->user) || !$this->user->can('galeria.view')) {
            abort(403, 'Lo siento !! ¡No estás autorizado a ver ninguna imagen!');
        }

        $galerias = Galeria::where('galeria_estado', '<>', 'eliminado')
                    ->join('sede', 'sede.sede_id', "=", "galeria.sede_id")
                    ->join('programa', 'programa.pro_id', "=", "galeria.pro_id")
                    ->select(
                        'galeria.*',
                        'programa.pro_nombre_abre',
                        'sede.sede_nombre_abre',
                    )
                    ->orderBy('galeria.updated_at', 'desc')
                    ->orderBy('sede.sede_nombre_abre')
                    ->orderBy('programa.pro_nombre_abre');
        if (!is_null($this->user->pro_ids)) {
            $proIds = json_decode($this->user->pro_ids, true);
            if (!empty($proIds)) {
                $galerias = $galerias->whereIn('galeria.pro_id', $proIds);
            }
        }

        // Filtrar por sedes si existen
        if (!is_null($this->user->sede_ids)) {
            $sedeIds = json_decode($this->user->sede_ids, true);
            if (!empty($sedeIds)) {
                $galerias = $galerias->whereIn('galeria.sede_id', $sedeIds);
            }
        }

        $galerias = $galerias->get();
        return view('backend.pages.galeria.index', compact('galerias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verifica si el usuario tiene permiso para crear galerías
        if (is_null($this->user) || !$this->user->can('galeria.create')) {
            abort(403, 'Lo siento !! ¡No estás autorizado a crear galerías!');
        }

        // Obtener todas las sedes junto con el nombre del departamento
        $sedes = Sede::join('departamento', 'sede.dep_id', '=', 'departamento.dep_id')
            ->select('sede.*', 'departamento.dep_nombre')
            ->get();

        // Obtener todos los programas disponibles
        $programas = Programa::select('programa.*')->get();

        // Filtrar programas según los pro_ids del usuario
        if (!is_null($this->user->pro_ids)) {
            $proIds = json_decode($this->user->pro_ids);
            if (!empty($proIds)) {
                $programas = $programas->whereIn('pro_id', $proIds);
            }
        }

        // Filtrar sedes según los sede_ids del usuario
        if (!is_null($this->user->sede_ids)) {
            $sedeIds = json_decode($this->user->sede_ids);
            if (!empty($sedeIds)) {
                $sedes = $sedes->whereIn('sede_id', $sedeIds);
            }
        }
        // Si solo hay un programa, preseleccionarlo
        $selectedPrograma = count($programas) === 1 ? $programas->first()->pro_id : null;

        // Si solo hay una sede, preseleccionarla
        $selectedSede = count($sedes) === 1 ? $sedes->first()->sede_id : null;
        return view('backend.pages.galeria.create', compact('sedes', 'programas', 'selectedPrograma', 'selectedSede'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'galeria_imagen' => 'required|image|mimes:png,jpg,jpeg|max:500', // Solo la imagen necesita validación de archivo
            'sede_id' => 'required|integer', // Validar que sea un campo requerido y entero
            'pro_id' => 'required|integer', // Validar que sea un campo requerido y entero
        ], [
            'galeria_imagen.image' => 'El archivo debe ser una imagen.',
            'galeria_imagen.mimes' => 'La imagen debe ser de tipo PNG, JPG o JPEG.',
            'galeria_imagen.max' => 'El tamaño máximo permitido para la imagen es de :max kilobytes.',
            'sede_id.required' => 'Debe seleccionar una sede.',
            'pro_id.required' => 'Debe seleccionar un programa.',
        ]);

        // Guardar la imagen en el directorio 'public/galeria'
        $imagePath = $request->file('galeria_imagen')->store('public/galeria');

        // Crear una nueva instancia de Galeria y guardar los datos
        $galeria = new Galeria();
        $galeria->galeria_imagen = basename($imagePath); // Guardar solo el nombre del archivo
        $galeria->sede_id = $request->sede_id;
        $galeria->pro_id = $request->pro_id;

        // Guardar en la base de datos
        $galeria->save();

        // Retornar respuesta JSON
        return response()->json([
            'success' => true,
            'message' => 'Imagen creada con éxito.',
            'data' => $galeria, // opcional: devuelve los datos de la galería
        ]);
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
        if (is_null($this->user) || !$this->user->can('galeria.edit')) {
            abort(403, 'Lo siento !! No estas autorizado a editar ninguna imagen !');
        }
        $galeria = Galeria::find($id);

        if (!$galeria) {
            abort(404, 'Galeria no encontrado.');
        }
        // Obtener todas las sedes junto con el nombre del departamento
        $sedes = Sede::join('departamento', 'sede.dep_id', '=', 'departamento.dep_id')
        ->select('sede.*', 'departamento.dep_nombre')
        ->get();

        // Obtener todos los programas disponibles
        $programas = Programa::select('programa.*')->get();

        // Filtrar programas según los pro_ids del usuario
        if (!is_null($this->user->pro_ids)) {
            $proIds = json_decode($this->user->pro_ids);
            if (!empty($proIds)) {
                $programas = $programas->whereIn('pro_id', $proIds);
            }
        }

        // Filtrar sedes según los sede_ids del usuario
        if (!is_null($this->user->sede_ids)) {
            $sedeIds = json_decode($this->user->sede_ids);
            if (!empty($sedeIds)) {
                $sedes = $sedes->whereIn('sede_id', $sedeIds);
            }
        }
        // Si solo hay un programa, preseleccionarlo
        $selectedPrograma = count($programas) === 1 ? $programas->first()->pro_id : null;
        // Si solo hay una sede, preseleccionarla
        $selectedSede = count($sedes) === 1 ? $sedes->first()->sede_id : null;
        return view('backend.pages.galeria.edit', compact('galeria','sedes', 'programas', 'selectedPrograma', 'selectedSede'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $galeria_id)
    {
        $request->validate([
            'galeria_imagen' => 'image|nullable|max:500',
            'pro_id' => 'required|exists:programa,pro_id',
            'sede_id' => 'required|exists:sede,sede_id',
        ]);

        $galeria = Galeria::findOrFail($galeria_id);

        if ($request->hasFile('galeria_imagen')) {
            // Agregar un mensaje para verificar si se está subiendo una imagen
            $path = $request->file('galeria_imagen')->store('galeria', 'public');
            $galeria->galeria_imagen = basename($path);
        } else {
            return response()->json(['error' => 'No se seleccionó ninguna imagen.'], 400);
        }

        $galeria->pro_id = $request->pro_id;
        $galeria->sede_id = $request->sede_id;
        $galeria->save();

        return response()->json([
            'success' => 'Galería actualizada exitosamente.',
            'galeria_imagen' => asset('storage/galeria/' . $galeria->galeria_imagen),
        ]);

    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (is_null($this->user) || !$this->user->can('galeria.delete')) {
            abort(403, 'Lo siento !! No estas autorizado a eliminar galeria !');
        }
        $galeria = Galeria::find($id);

        if (!$galeria) {
            return response()->json(['error' => 'Galería no encontrada.'], 404);
        }
        $galeria = Galeria::where('galeria_id', $id)->first();

        $galeria->galeria_estado = 'eliminado';

        $galeria->save();

        return response()->json(['success' => 'Galería eliminada exitosamente.']);
    }



    public function estado(string $id)
    {
        $galeria = Galeria::where('galeria_id', $id)->first();
        if ($galeria->galeria_estado == 'activo') {
            $galeria->galeria_estado = 'inactivo';
        } else {
            $galeria->galeria_estado = 'activo';
        }
        $galeria->save();

        return back()->with('success', 'Estado actualizado');
    }
}
