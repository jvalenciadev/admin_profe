<?php
namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Programa;
use App\Models\ProgramaSedeTurno;
use App\Models\ProgramaRestriccion;
use App\Models\Galeria;
use App\Models\Blog;
use App\Models\Profe;
use App\Models\Sede;
use App\Models\Video;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class FrontendController extends Controller
{
    public function listEventos()
    {
        try {
            $eventos = Evento::select([
                    'evento.eve_id', 'evento.eve_nombre', 'evento.eve_descripcion', 'evento.eve_banner', 'evento.eve_afiche',
                    'evento.eve_fecha', 'evento.eve_inscripcion', 'evento.eve_asistencia',
                    'evento.eve_ins_hora_asis_habilitado', 'evento.eve_ins_hora_asis_deshabilitado',
                    'evento.eve_lugar', 'evento.eve_total_inscrito', 'tipo_evento.et_id',
                    'tipo_evento.et_nombre'
                ])
                ->selectRaw('(SELECT JSON_ARRAYAGG(pm.pm_nombre)
                              FROM programa_modalidad pm
                              WHERE JSON_CONTAINS(evento.pm_ids, CONCAT(\'"\', pm.pm_id, \'"\'))
                              ) AS modalidades')
                ->join('tipo_evento', 'evento.et_id', '=', 'tipo_evento.et_id')
                ->where('evento.eve_estado', 'activo')
                ->orderBy('evento.eve_fecha', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'codigo_http' => 200,
                'respuesta' => $eventos,
                'error' => null
            ], 200);

        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error de base de datos: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getEventoById($id)
    {
        try {
            $evento = Evento::select([
                    'evento.eve_id', 'evento.eve_nombre', 'evento.eve_descripcion', 'evento.eve_banner', 'evento.eve_afiche',
                    'evento.eve_fecha', 'evento.eve_inscripcion', 'evento.eve_asistencia',
                    'evento.eve_ins_hora_asis_habilitado', 'evento.eve_ins_hora_asis_deshabilitado',
                    'evento.eve_lugar', 'evento.eve_total_inscrito', 'tipo_evento.et_id',
                    'tipo_evento.et_nombre'
                ])
                ->selectRaw('(SELECT JSON_ARRAYAGG(pm.pm_nombre)
                              FROM programa_modalidad pm
                              WHERE JSON_CONTAINS(evento.pm_ids, CONCAT(\'"\', pm.pm_id, \'"\'))
                              ) AS modalidades')
                ->join('tipo_evento', 'evento.et_id', '=', 'tipo_evento.et_id')
                ->where('evento.eve_estado', 'activo')
                ->where('evento.eve_id', $id)
                ->first();

            if (!$evento) {
                return response()->json([
                    'status' => 'error',
                    'codigo_http' => 404,
                    'respuesta' => null,
                    'error' => 'Evento no encontrado'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'codigo_http' => 200,
                'respuesta' => $evento,
                'error' => null
            ], 200);

        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error de base de datos: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }
    public function listProgramas()
    {
        try {
            // Realizamos la consulta y agrupamos por tipo
            $programas = Programa::join('programa_tipo', 'programa_tipo.pro_tip_id', '=', 'programa.pro_tip_id')
                ->join('programa_modalidad', 'programa_modalidad.pm_id', '=', 'programa.pm_id')
                ->join('programa_version', 'programa_version.pv_id', '=', 'programa.pv_id')
                ->join('programa_duracion', 'programa_duracion.pd_id', '=', 'programa.pd_id')
                ->where('pro_estado', 'activo')
                ->orderBy('programa_tipo.pro_tip_nombre', 'ASC') // Ordenar por tipo
                ->orderBy('programa.pro_id', 'DESC') // Ordenar dentro del tipo
                ->select('programa.*', 'programa_tipo.pro_tip_nombre','programa_duracion.pd_nombre',
                'programa_duracion.pd_semana','programa_modalidad.pm_nombre','programa_version.pv_nombre','programa_version.pv_romano','pv_gestion')
                ->get(); // Agrupar por tipo

            // Retornamos la respuesta en formato JSON
            return response()->json([
                'status' => 'success',
                'codigo_http' => 200,
                'respuesta' => $programas,
                'error' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error interno del servidor'
            ], 500);
        }
    }
    public function getProgramaById($pro_id)
    {
        try {
            // Obtener el programa
            $programa = Programa::join('programa_tipo', 'programa_tipo.pro_tip_id', '=', 'programa.pro_tip_id')
            ->join('programa_modalidad', 'programa_modalidad.pm_id', '=', 'programa.pm_id')
            ->join('programa_version', 'programa_version.pv_id', '=', 'programa.pv_id')
            ->join('programa_duracion', 'programa_duracion.pd_id', '=', 'programa.pd_id')
            ->where('pro_estado', 'activo')
            ->orderBy('programa_tipo.pro_tip_nombre', 'ASC') // Ordenar por tipo
            ->orderBy('programa.pro_id', 'DESC') // Ordenar dentro del tipo
            ->select('programa.*', 'programa_tipo.pro_tip_nombre','programa_duracion.pd_nombre',
            'programa_duracion.pd_semana','programa_modalidad.pm_nombre','programa_version.pv_nombre','programa_version.pv_romano','pv_gestion')
            ->where('pro_estado', 'activo')->where('pro_id', $pro_id)->first();

            // Si no existe el programa, retornar error 404
            if (!$programa) {
                return response()->json([
                    'status' => 'error',
                    'codigo_http' => 404,
                    'respuesta' => null,
                    'error' => 'Programa no encontrado'
                ], 404);
            }

            // Obtener los turnos y sedes asociados
            $programa_sede_turno = ProgramaSedeTurno::join('sede', 'programa_sede_turno.sede_id', '=', 'sede.sede_id')
                ->join('departamento', 'sede.dep_id', '=', 'departamento.dep_id')
                ->where('pro_id', $pro_id)
                ->selectRaw('
                    sede.sede_nombre,
                    departamento.dep_nombre,
                    sede.sede_contacto_1,
                    programa_sede_turno.pro_tur_ids,
                    sede.sede_contacto_2,
                    sede.sede_id,
                    (SELECT JSON_ARRAYAGG(pt.pro_tur_nombre)
                        FROM programa_turno pt
                        WHERE JSON_CONTAINS(programa_sede_turno.pro_tur_ids, CONCAT(\'"\', pt.pro_tur_id, \'"\'))
                    ) AS programaturno'
                )->get();

            // Obtener las restricciones asociadas
            $restriccion = ProgramaRestriccion::where('pr_estado', 'activo')
            ->where('pro_id', $pro_id)
            ->select('res_descripcion')
            ->first();
            // Obtener las galerías por programa, agrupadas por sede
            $galeriasPorPrograma = Galeria::select(
                        'galeria.*',
                    'programa.pro_nombre_abre',
                    'programa.pro_nombre',
                    'departamento.dep_nombre',
                    'departamento.dep_abreviacion',
                    'sede.sede_nombre_abre',
                    'sede.sede_nombre'
                )
                    ->join('sede', 'galeria.sede_id', '=', 'sede.sede_id')
                    ->join('departamento', 'departamento.dep_id', '=', 'sede.dep_id')
                    ->join('programa', 'galeria.pro_id', '=', 'programa.pro_id')
                    ->where('galeria.galeria_estado', 'activo')
                    ->where('programa.pro_codigo', $programa->pro_codigo)
                    ->orderBy('galeria.updated_at', 'desc')
                    ->get()->take(20); // Toma solo 3 imágenes por sede


            // Preparar la respuesta JSON
            return response()->json([
                'status' => 'success',
                'codigo_http' => 200,
                'respuesta' => [
                    'programa' => $programa,
                    'programa_sede_turno' => $programa_sede_turno,
                    'restriccion' => $restriccion,
                    'galeriasPorPrograma' => $galeriasPorPrograma
                ],
                'error' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error interno del servidor'. $e->getMessage()
            ], 500);
        }
    }
    public function listBlogs() {
        try {
            // Obtener los blogs activos y ordenarlos por blog_id descendente
            $blogs = Blog::where('blog_estado', 'activo')
                        ->orderBy('blog_id', 'DESC')
                        ->get();

            // Verificar si hay blogs disponibles
            if ($blogs->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'codigo_http' => 404,
                    'respuesta' => null,
                    'error' => 'No se encontraron novedades.'
                ], 404);
            }

            // Respuesta exitosa
            return response()->json([
                'status' => 'success',
                'codigo_http' => 200,
                'respuesta' => $blogs,
                'error' => null
            ], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            // Error de consulta SQL (por ejemplo, problema con la base de datos)
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error en la consulta SQL: ' . $e->getMessage()
            ], 500);

        } catch (\Exception $e) {
            // Cualquier otro error inesperado
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getBlogById($id) {
        try {
            // Buscar el blog por ID y asegurarse de que esté activo
            $blog = Blog::where('blog_estado', 'activo')->find($id);

            // Verificar si el blog existe
            if (!$blog) {
                return response()->json([
                    'status' => 'error',
                    'codigo_http' => 404,
                    'respuesta' => null,
                    'error' => 'No se encontró el novedades'
                ], 404);
            }

            // Respuesta exitosa
            return response()->json([
                'status' => 'success',
                'codigo_http' => 200,
                'respuesta' => $blog,
                'error' => null
            ], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            // Error de consulta SQL (por ejemplo, problema con la base de datos)
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error en la consulta SQL: ' . $e->getMessage()
            ], 500);

        } catch (\Exception $e) {
            // Cualquier otro error inesperado
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getProfe() {
        try {
            // Obtener la primera fila de la tabla Profe
            $profe = Profe::first();

            // Obtener todas las sedes activas, uniendo con la tabla departamento

            // Verificar si no hay datos en ambas consultas
            if (!$profe) {
                return response()->json([
                    'status' => 'error',
                    'codigo_http' => 404,
                    'respuesta' => null,
                    'error' => 'No se encontraron registros en Profe.'
                ], 404);
            }

            // Respuesta exitosa
            return response()->json([
                'status' => 'success',
                'codigo_http' => 200,
                'respuesta' => $profe,
                'error' => null
            ], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            // Error de consulta SQL
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error en la consulta SQL: ' . $e->getMessage()
            ], 500);

        } catch (\Exception $e) {
            // Cualquier otro error inesperado
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }

    public function listSedes() {
        try {
            // Obtener todas las sedes activas con su departamento
            $sedes = Sede::join('departamento', 'departamento.dep_id', '=', 'sede.dep_id')
                ->select('sede.*', 'departamento.dep_nombre')
                ->where('sede.sede_estado', 'activo')
                ->orderBy('sede.sede_id', 'DESC')
                ->get();

            // Verificar si hay sedes disponibles
            if ($sedes->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'codigo_http' => 404,
                    'respuesta' => null,
                    'error' => 'No se encontraron sedes activas.'
                ], 404);
            }

            // Respuesta exitosa
            return response()->json([
                'status' => 'success',
                'codigo_http' => 200,
                'respuesta' => $sedes,
                'error' => null
            ], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            // Error en la consulta SQL
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error en la consulta SQL: ' . $e->getMessage()
            ], 500);

        } catch (\Exception $e) {
            // Cualquier otro error inesperado
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getSedeById($sede_id) {
        try {
            // Obtener los detalles de la sede con su departamento
            $sede = Sede::join('departamento', 'departamento.dep_id', '=', 'sede.dep_id')
                ->select('sede.*', 'departamento.dep_nombre')
                ->where('sede.sede_estado', 'activo')
                ->where('sede.sede_id', $sede_id)
                ->first();

            // Validar si la sede existe
            if (!$sede) {
                return response()->json([
                    'status' => 'error',
                    'codigo_http' => 404,
                    'respuesta' => null,
                    'error' => 'Sede no encontrada'
                ], 404);
            }

            // Obtener las galerías agrupadas por programa
            $galerias = Galeria::select(
                    'galeria.*',
                    'programa.pro_nombre_abre',
                    'programa.pro_nombre',
                    'departamento.dep_nombre',
                    'departamento.dep_abreviacion',
                    'sede.sede_nombre_abre',
                    'sede.sede_nombre'
                )
                ->join('sede', 'galeria.sede_id', '=', 'sede.sede_id')
                ->join('departamento', 'departamento.dep_id', '=', 'sede.dep_id')
                ->join('programa', 'galeria.pro_id', '=', 'programa.pro_id')
                ->where('galeria.galeria_estado', 'activo')
                ->where('sede.sede_id', $sede_id)
                ->orderBy('galeria.updated_at', 'desc')
                ->get();

            // Respuesta exitosa
            return response()->json([
                'status' => 'success',
                'codigo_http' => 200,
                'respuesta' => [
                    'sede' => $sede,
                    'galerias' => $galerias
                ],
                'error' => null
            ], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            // Error de consulta SQL
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error en la consulta SQL: ' . $e->getMessage()
            ], 500);

        } catch (\Exception $e) {
            // Cualquier otro error inesperado
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }
    public function listGalerias(){
        try {
            // Obtener las galerías activas agrupadas por programa
            $galeriasPorPrograma = Galeria::select(
                    'galeria.*',
                    'programa.pro_nombre_abre',
                    'programa.pro_nombre',
                    'departamento.dep_nombre',
                    'departamento.dep_abreviacion',
                    'sede.sede_nombre_abre',
                    'sede.sede_nombre'
                )
                ->join('sede', 'galeria.sede_id', '=', 'sede.sede_id')
                ->join('departamento', 'departamento.dep_id', '=', 'sede.dep_id')
                ->join('programa', 'galeria.pro_id', '=', 'programa.pro_id')
                ->where('galeria.galeria_estado', 'activo')
                ->orderBy('galeria.updated_at', 'desc')
                ->get();

            // Verificar si hay galerías disponibles
            if ($galeriasPorPrograma->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'codigo_http' => 404,
                    'respuesta' => null,
                    'error' => 'No se encontraron galerías activas.'
                ], 404);
            }

            // Respuesta exitosa
            return response()->json([
                'status' => 'success',
                'codigo_http' => 200,
                'respuesta' => $galeriasPorPrograma,
                'error' => null
            ], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            // Error en la consulta SQL
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error en la consulta SQL: ' . $e->getMessage()
            ], 500);

        } catch (\Exception $e) {
            // Cualquier otro error inesperado
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }
    public function listVideos(){
        try {
            // Obtener todos los videos
            $videos = Video::get();

            // Verificar si hay videos disponibles
            if ($videos->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'codigo_http' => 404,
                    'respuesta' => null,
                    'error' => 'No se encontraron videos.'
                ], 404);
            }

            // Respuesta exitosa con los videos
            return response()->json([
                'status' => 'success',
                'codigo_http' => 200,
                'respuesta' => $videos,
                'error' => null
            ], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            // Error en la consulta SQL
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error en la consulta SQL: ' . $e->getMessage()
            ], 500);

        } catch (\Exception $e) {
            // Cualquier otro error inesperado
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getAppInfo() {
        try {
            // Datos de la aplicación
            $appInfo = [
                'id' => 1,
                'logo' => 'https://profe.minedu.gob.bo/backend/image/logoprofe.png',
                'icono' => 'https://profe.minedu.gob.bo/backend/image/logo.jpg',
                'nombre' => 'Programa PROFE',
                'version_actual' => '1.2.3',
                'version_minima' => '1.0.0',
                'ultima_actualizacion' => '2024-02-28',
                'playstore_url' => 'https://play.google.com/store/apps/details?id=com.miapp',
                'sitio_web' => 'https://profe.minedu.gob.bo',
                'contacto_soporte' => 'profecorreos@iipp.edu.bo',
                'estado_mantenimiento' => false, // true si la app está en mantenimiento
                'novedades' => [
                    'Corrección de errores y mejoras de rendimiento',
                    'Nueva función de notificaciones en tiempo real',
                    'Optimización de la interfaz de usuario'
                ],
                'terminos_url' => 'https://profe.minedu.gob.bo/terminos',
                'privacidad_url' => 'https://profe.minedu.gob.bo/privacidad'
            ];

            // Respuesta JSON
            return response()->json([
                'status' => 'success',
                'codigo_http' => 200,
                'respuesta' => $appInfo,
                'error' => null
            ], 200);

        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'status' => 'error',
                'codigo_http' => 500,
                'respuesta' => null,
                'error' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }

}
