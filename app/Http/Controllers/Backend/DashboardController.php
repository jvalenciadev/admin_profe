<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ProgramaInscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }


    public function index()
    {
        if (is_null($this->user) || !$this->user->can('dashboard.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view dashboard !');
        }

        $grupo_programa = DB::table('programa_inscripcion')
        ->join('sede', 'sede.sede_id', '=', 'programa_inscripcion.sede_id')
        ->join('departamento', 'departamento.dep_id', '=', 'sede.dep_id')
        ->join('programa', 'programa.pro_id', '=', 'programa_inscripcion.pro_id')
        ->join('programa_turno', 'programa_turno.pro_tur_id', '=', 'programa_inscripcion.pro_tur_id')
        ->select(
            'programa.pro_id',
            'programa.pro_nombre_abre as PROGRAMA',
            DB::raw('CONCAT(departamento.dep_abreviacion, "-", sede.sede_nombre_abre) as SEDE'),
            'programa_turno.pro_tur_nombre as TURNO',
            DB::raw('COUNT(programa_inscripcion.pi_id) as CANTIDAD')
        )
        ->groupBy('programa.pro_id', 'programa.pro_nombre_abre',
         'sede.sede_id', 'departamento.dep_abreviacion',
         'sede.sede_nombre_abre', 'programa_turno.pro_tur_nombre', 'programa_turno.pro_tur_id')
        ->orderBy('programa.pro_id')
        ->orderBy('sede.sede_id')
        ->orderBy(DB::raw('CANTIDAD'))
        ->get();
        $participantes = DB::table('participantes_ajedrez as pa')
            ->join('programa_inscripcion as pi', 'pi.pi_id', '=', 'pa.pi_id')
            ->join('map_persona as per', 'per.per_id', '=', 'pi.per_id')
            ->join('sede as se', 'se.sede_id', '=', 'pi.sede_id')
            ->join('departamento as de', 'de.dep_id', '=', 'se.dep_id')
            ->select(
                DB::raw("CONCAT(COALESCE(per.per_nombre1, ''), ' ', COALESCE(per.per_nombre2, ''), ' ', COALESCE(per.per_apellido1, ''), ' ', COALESCE(per.per_apellido2, '')) AS nombre_completo"),
                'de.dep_nombre',
                'pa.updated_at'
            )
            ->where('pa.pa_id', function ($query) {
                $query->select('sub_pa.pa_id')
                    ->from('participantes_ajedrez as sub_pa')
                    ->join('programa_inscripcion as sub_pi', 'sub_pi.pi_id', '=', 'sub_pa.pi_id')
                    ->join('sede as sub_se', 'sub_se.sede_id', '=', 'sub_pi.sede_id')
                    ->whereColumn('sub_se.dep_id', 'de.dep_id')
                    ->where('sub_pa.pa_estado', '=', 'activo')
                    ->orderBy('sub_pa.updated_at', 'desc')
                    ->limit(1);
            })
            ->get();
        $inscritosDep = DB::table('programa_inscripcion as pi')
            ->join('sede as sed', 'pi.sede_id', '=', 'sed.sede_id')
            ->join('departamento as dep', 'sed.dep_id', '=', 'dep.dep_id')
            ->select('dep.dep_abreviacion', DB::raw('COUNT(pi.pi_id) as total_inscripciones'))
            ->groupBy('dep.dep_abreviacion')
            ->where('pi.pi_estado','activo')
            ->whereBetween('pi.pro_id', [12, 22])
            ->get();
        $inscritosSede = DB::table('programa_inscripcion as pi')
            ->join('sede as sed', 'pi.sede_id', '=', 'sed.sede_id')
            ->join('departamento as dep', 'sed.dep_id', '=', 'dep.dep_id')
            ->select('dep.dep_abreviacion', 'sed.sede_nombre_abre', DB::raw('COUNT(pi.pi_id) as total_inscripciones'))
            ->groupBy('dep.dep_abreviacion', 'sed.sede_nombre_abre')
            ->where('pi.pi_estado','activo')
            ->whereBetween('pi.pro_id', [12, 22])
            ->get();
        $total_inscritos = count(ProgramaInscripcion::select('pi_id')
        ->where('pi_estado','=',"activo")
        ->whereBetween('pro_id', [12, 22])
        ->where('pie_id','=',"2")->get());
        $total_roles = count(Role::select('id')->get());
        $total_admins = count(Admin::select('id')->get());
        $total_permissions = count(Permission::select('id')->get());
        $programas = DB::table('programa_inscripcion as pi')
            ->join('sede as sed', 'sed.sede_id', '=', 'pi.sede_id')
            ->join('programa as pro', 'pro.pro_id', '=', 'pi.pro_id')
            ->join('departamento as dep', 'dep.dep_id', '=', 'sed.dep_id')
            ->select(
                'pro.pro_nombre_abre',
                DB::raw('SUM(IF(dep.dep_id = 1, 1, 0)) AS CHUQUISACA'),
                DB::raw('SUM(IF(dep.dep_id = 2, 1, 0)) AS LA_PAZ'),
                DB::raw('SUM(IF(dep.dep_id = 3, 1, 0)) AS COCHABAMBA'),
                DB::raw('SUM(IF(dep.dep_id = 4, 1, 0)) AS ORURO'),
                DB::raw('SUM(IF(dep.dep_id = 5, 1, 0)) AS POTOSI'),
                DB::raw('SUM(IF(dep.dep_id = 6, 1, 0)) AS TARIJA'),
                DB::raw('SUM(IF(dep.dep_id = 7, 1, 0)) AS SANTA_CRUZ'),
                DB::raw('SUM(IF(dep.dep_id = 8, 1, 0)) AS BENI'),
                DB::raw('SUM(IF(dep.dep_id = 9, 1, 0)) AS PANDO'),
                DB::raw('SUM(IF(dep.dep_id IN (1,2,3,4,5,6,7,8,9), 1, 0)) AS TOTAL')
            )
            ->where('pi.pi_estado','activo')
            ->where('pi.pie_id',2)
            ->whereBetween('pi.pro_id', [12, 22])
            ->groupBy('pro.pro_nombre_abre', 'pro.pro_id')
            ->orderBy('pro.pro_id', 'ASC')
            ->get();

        return view('backend.pages.dashboard.index', compact(
            'total_admins', 'total_roles', 'total_permissions',
            'inscritosDep','inscritosSede','total_inscritos',
            'participantes','grupo_programa', 'programas'
        ));
    }
}
