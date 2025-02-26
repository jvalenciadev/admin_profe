@php
    use Illuminate\Support\Facades\DB;
    $usr = Auth::guard('admin')->user();
    $sedes = DB::table('sede');
    if (!is_null($usr->sede_ids)) {
        $sedeIds = json_decode($usr->sede_ids);
        if (!empty($sedeIds)) {
            // Verifica si $proIds no está vacío
            $sedes->whereIn('sede.sede_id', $sedeIds);
        }
    }
    $sedes = $sedes->get();
    $programas = DB::table('programa_sede_turno');
    $progrs = DB::table('programa');
    // Verifica si el usuario tiene pro_ids
    if (!is_null($usr->pro_ids)) {
        $proIds = json_decode($usr->pro_ids);
        if (!empty($proIds)) {
            // Filtra los registros para pro_id 8
            $progrs->whereIn('pro_id', $proIds);
        }
    }
    // Obtén los registros
    $programas = $programas->get();
    $progrs = $progrs->get();

    // Verifica si hay registros para pro_id 8
    $hasProId8 = $progrs->contains('pro_id', 8);
@endphp
<style>

</style>
<!-- nabar area start -->
<nav class="pcoded-navbar">
    <div class="pcoded-inner-navbar main-menu">
        <div class="pcoded-navigatio-lavel">ADMINISTRADOR</div>
        <ul class="pcoded-item pcoded-left-item">
            @if ($usr->can('dashboard.view'))
                <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                        <span class="pcoded-mtext">Dashboard</span>
                    </a>
                </li>
            @endif
            @if ($usr->can('role.create') || $usr->can('role.view') || $usr->can('role.edit') || $usr->can('role.delete'))
                <li
                    class="pcoded-hasmenu {{ Route::is('admin.roles.create') || Route::is('admin.roles.index') || Route::is('admin.roles.edit') ? 'pcoded-trigger' : '' }}">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="icofont icofont-shield"></i></span>
                        <span>
                            Roles & Permisos
                        </span></a>
                    <ul class="pcoded-submenu">
                        @if ($usr->can('role.view'))
                            <li
                                class="{{ Route::is('admin.roles.index') || Route::is('admin.roles.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.roles.index') }}">
                                    <span class="pcoded-mtext">Todos Roles
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if ($usr->can('role.create'))
                            <li class="{{ Route::is('admin.roles.create') ? 'active' : '' }}">
                                <a href="{{ route('admin.roles.create') }}">
                                    <span class="pcoded-mtext">Crear
                                        Rol</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif


            @if ($usr->can('admin.create') || $usr->can('admin.view') || $usr->can('admin.edit') || $usr->can('admin.delete'))
                <li
                    class="pcoded-hasmenu  {{ Route::is('admin.admins.create') || Route::is('admin.admins.index') || Route::is('admin.admins.edit') ? 'pcoded-trigger' : '' }}">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="icofont icofont-shield-alt"></i></span>
                        <span>
                            Admins
                        </span></a>
                    </a>
                    <ul class="pcoded-submenu">

                        @if ($usr->can('admin.view'))
                            <li
                                class="{{ Route::is('admin.admins.index') || Route::is('admin.admins.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.admins.index') }}"><span class="pcoded-mtext">Todos Admins</a>
                            </li>
                        @endif

                        @if ($usr->can('admin.create'))
                            <li class="{{ Route::is('admin.admins.create') ? 'active' : '' }}">
                                <a href="{{ route('admin.admins.create') }}"><span class="pcoded-mtext">Crear Admin</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            </li>
            @if ($usr->can('migracion.view'))
                <li
                    class="pcoded-hasmenu {{ Route::is('migration.distrito.index') || Route::is('migration.departamento.index') || Route::is('migration.especialidad.index') || Route::is('migration.cargo.index') || Route::is('migration.unidadeducativa.index') || Route::is('migration.otros.index') || Route::is('migration.usuarios.index') || Route::is('migration.inscripciones.index') ? 'pcoded-trigger' : '' }}">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="feather icon-sidebar"></i></span>
                        <span class="pcoded-mtext">Migraciones</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li
                            class="pcoded-hasmenu {{ Route::is('migration.distrito.index') || Route::is('migration.departamento.index') ? 'pcoded-trigger' : '' }}">
                            <a href="javascript:void(0)">
                                <span class="pcoded-mtext">Ciudad</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="{{ Route::is('migration.distrito.index') ? 'active' : '' }}">
                                    <a href="{{ route('migration.distrito.index') }}">
                                        <span class="pcoded-mtext">Distrito</span>
                                    </a>
                                </li>
                                <li class="{{ Route::is('migration.departamento.index') ? 'active' : '' }}">
                                    <a href="{{ route('migration.departamento.index') }}">
                                        <span class="pcoded-mtext">Departamento</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="pcoded-hasmenu {{ Route::is('migration.especialidad.index') || Route::is('migration.cargo.index') || Route::is('migration.unidadeducativa.index') || Route::is('migration.otros.index') ? 'pcoded-trigger' : '' }}">
                            <a href="javascript:void(0)">
                                <span class="pcoded-mtext">Otros</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="{{ Route::is('migration.especialidad.index') ? 'active' : '' }}">
                                    <a href="{{ route('migration.especialidad.index') }}">
                                        <span class="pcoded-mtext">Especialidad</span>
                                    </a>
                                </li>
                                <li class="{{ Route::is('migration.cargo.index') ? 'active' : '' }}">
                                    <a href="{{ route('migration.cargo.index') }}">
                                        <span class="pcoded-mtext">Cargo</span>
                                    </a>
                                </li>
                                <li class="{{ Route::is('migration.unidadeducativa.index') ? 'active' : '' }}">
                                    <a href="{{ route('migration.unidadeducativa.index') }}">
                                        <span class="pcoded-mtext">Unidad Educativa</span>
                                    </a>
                                </li>
                                <li class="{{ Route::is('migration.otros.index') ? 'active' : '' }}">
                                    <a href="{{ route('migration.otros.index') }}">
                                        <span class="pcoded-mtext">Otros</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ Route::is('migration.usuarios.index') ? 'active' : '' }}">
                            <a href="{{ route('migration.usuarios.index') }}">
                                <span class="pcoded-mtext">Personas</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('migration.inscripciones.index') ? 'active' : '' }}">
                            <a href="{{ route('migration.inscripciones.index') }}">
                                <span class="pcoded-mtext">Inscripciones</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            
            @if ($usr->can('configuracion_programa.view'))
                <li
                    class="pcoded-hasmenu {{ Route::is('configuracion.programa.index') ||
                    Route::is('configuracion.sede.index') ||
                    Route::is('configuracion.evento.index') ||
                    Route::is('configuracion.restriccion.index')
                        ? 'pcoded-trigger'
                        : '' }}">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="feather icon-sidebar"></i></span>
                        <span class="pcoded-mtext">Configuraciones</span>
                        {{-- <span class="pcoded-badge label label-warning">NEW</span> --}}
                    </a>

                    <ul class="pcoded-submenu">
                        <li class="{{ Route::is('configuracion.programa.index') ? 'active' : '' }}">
                            <a href="{{ route('configuracion.programa.index') }}">
                                <span class="pcoded-mtext">Programa</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('configuracion.sede.index') ? 'active' : '' }}">
                            <a href="{{ route('configuracion.sede.index') }}">
                                <span class="pcoded-mtext">Sede Cupos - Turnos</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('configuracion.evento.index') ? 'active' : '' }}">
                            <a href="{{ route('configuracion.evento.index') }}">
                                <span class="pcoded-mtext">Evento</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('configuracion.restriccion.index') ? 'active' : '' }}">
                            <a href="{{ route('configuracion.restriccion.index') }}">
                                <span class="pcoded-mtext">Programa Restricciones</span>
                            </a>
                        </li>

                    </ul>
                </li>
            @endif
            @if ($usr->can('profe.view'))
                <li class="{{ Route::is('admin.profe.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.profe.index') }}">
                        <span class="pcoded-micon"><i class="feather icon-menu"></i></span>
                        <span class="pcoded-mtext">Información</span>
                    </a>
                </li>
            @endif
            @if (
                $usr->can('admin.view') ||
                    $usr->can('programa.view') ||
                    $usr->can('comunicado.view') ||
                    $usr->can('evento.view') ||
                    $usr->can('blog.view') ||
                    $usr->can('galeria.view'))
                <li class="pcoded-hasmenu">

                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="feather icon-sidebar"></i></span>
                        <span class="pcoded-mtext">Contenido</span>
                        {{-- <span class="pcoded-badge label label-warning">NEW</span> --}}
                    </a>

                    <ul class="pcoded-submenu">
                        @if ($usr->can('admin.view'))
                            <li class=" ">
                                <a href="{{ route('admin.responsable.index') }}">
                                    <span class="pcoded-mtext">Responsable Profe</span>
                                </a>
                            </li>
                        @endif
                        @if ($usr->can('programa.view'))
                            <li class=" ">
                                <a href="{{ route('admin.programa.index') }}">
                                    <span class="pcoded-mtext">Programas</span>
                                </a>
                            </li>
                        @endif
                        @if ($usr->can('comunicado.view'))
                            <li class=" ">
                                <a href="{{ route('admin.comunicado.index') }}">
                                    <span class="pcoded-mtext">Comunicados</span>
                                </a>
                            </li>
                        @endif
                        @if ($usr->can('evento.view'))
                            <li class=" ">
                                <a href="{{ route('admin.evento.index') }}">
                                    <span class="pcoded-mtext">Eventos</span>
                                </a>
                            </li>
                        @endif
                        @if ($usr->can('blog.view'))
                            <li class=" ">
                                <a href="{{ route('admin.blog.index') }}">
                                    <span class="pcoded-mtext">Blogs</span>
                                </a>
                            </li>
                        @endif
                        @if ($usr->can('galeria.view'))
                            <li class=" ">
                                <a href="{{ route('admin.galeria.index') }}">
                                    <span class="pcoded-mtext">Galeria</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if ($usr->can('responsable.view'))
                <li class="{{ Route::is('admin.inscripcion.buscadorpersona') ? 'active' : '' }}">
                    <a href="{{ route('admin.inscripcion.buscadorpersona') }}">
                        <span class="pcoded-micon"><i class="feather icon-menu"></i></span>
                        <span class="pcoded-mtext">Buscardor</span>
                    </a>
                </li>
            @endif
            @if ($usr->can('personaprograma.view'))
                <li class="{{ Route::is('admin.participantes.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.participantes.index') }}">
                        <span class="pcoded-micon"><i class="icofont icofont-business-man"></i></span>
                        <span class="pcoded-mtext">Participantes</span>
                    </a>
                </li>
            @endif
            @if ($usr->can('certificacion.view'))
                <li class="">
                    <a href="">
                        <span class="pcoded-micon"><i class="icofont icofont-certificate-alt-2"></i></span>
                        <span class="pcoded-mtext">Part. Certificados</span>
                        <span class="pcoded-badge label label-warning">Nuevo</span>
                    </a>
                </li>
            @endif
            @if ($usr->can('inscripcion.view'))
                <li class="{{ Route::is('admin.inscripcion.buscadorpersona') ? 'active' : '' }}">
                    <a href="{{ route('admin.acta.index') }}" class="new-feature">
                        <span class="pcoded-micon"><i class="feather icon-server"></i></span>
                        <span class="pcoded-mtext">Académico</span>
                    </a>
                </li>
            @endif
            @if ($usr->can('sede.view'))
                <li class="{{ Route::is('admin.sede.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.sede.index') }}">
                        <span class="pcoded-micon"><i class="feather icon-menu"></i></span>
                        <span class="pcoded-mtext">Sedes</span>
                    </a>
                </li>
            @endif
            @if ($usr->can('inscripcion.view'))
                <li class="{{ Route::is('admin.inscripcion.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.solicitudes.index') }}">
                        <span class="pcoded-micon"><i class="icofont icofont-map-pins"></i></span>
                        <span class="pcoded-mtext">Solicitudes</span>
                        <span class="pcoded-badge label label-warning">NEW</span>
                    </a>
                </li>
            @endif
            @if ($usr->can('inscripcion.view'))
                <li class="pcoded-hasmenu {{ Route::is('admin.inscripcion.index') ? 'pcoded-trigger' : '' }}">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="feather icon-sidebar"></i></span>
                        <span class="pcoded-mtext">Inscripciones</span>
                    </a>
                    <ul class="pcoded-submenu">
                        @foreach ($sedes as $sede)
                            @php
                                // Encripta el sede_id
                                $sedeencript = encrypt($sede->sede_id);

                                // Genera los parámetros de la ruta
                                $routeParameters = ['sede_id' => $sedeencript];

                                // Obtiene el sede_id actual de la URL
                                $currentSedeId = request()->route('sede_id');

                                // Decifra el sede_id actual
                                $decryptedCurrentSedeId = $currentSedeId ? decrypt($currentSedeId) : null;

                                // Comprueba si el ítem debe estar activo
                                $isActive =
                                    Route::is('admin.inscripcion.index') && $decryptedCurrentSedeId == $sede->sede_id;
                            @endphp
                            <li class="{{ $isActive ? 'active' : '' }}">
                                <a href="{{ route('admin.inscripcion.index', $routeParameters) }}">
                                    <span class="pcoded-micon"><i class="feather icon-menu"></i></span>
                                    <span class="pcoded-mtext">{{ $sede->sede_nombre }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif


            {{-- @if ($usr->can('calificacion.view'))
                <div class="pcoded-navigatio-lavel">CALIFICACION</div>
                @foreach ($sedes as $sede)
                    @php
                        // Encripta el sede_id
                        $sedeencript = encrypt($sede->sede_id);

                        // Obtiene el sede_id actual y decifra si es necesario
                        $currentSedeId = request()->route('sede_id');
                        $decryptedCurrentSedeId = $currentSedeId ? decrypt($currentSedeId) : null;

                        // Verifica si el menú de sede debe estar activo
                        $isSedeActive = $decryptedCurrentSedeId == $sede->sede_id;
                    @endphp
                    <li class="pcoded-hasmenu {{ $isSedeActive ? 'pcoded-trigger' : '' }}">
                        <a href="javascript:void(0)">
                            <span class="pcoded-micon"><i class="feather icon-sidebar"></i></span>
                            <span class="pcoded-mtext">{{ $sede->sede_nombre_abre }}</span>
                        </a>
                        <ul class="pcoded-submenu">
                            @foreach ($progrs as $programa)
                                @php
                                    // Encripta el pro_id
                                    $proencript = encrypt($programa->pro_id);

                                    // Obtiene el pro_id actual y decifra si es necesario
                                    $currentProId = request()->route('pro_id');
                                    $decryptedCurrentProId = $currentProId ? decrypt($currentProId) : null;

                                    // Verifica si estamos en la ruta de calificación y si el programa coincide
                                    $isProgramaActive =
                                        request()->routeIs('admin.calificacion.index') &&
                                        $decryptedCurrentProId == $programa->pro_id;

                                    // Verifica si estamos en la ruta de investigación y si el programa coincide
                                    $isInvestigacionActive =
                                        request()->routeIs('admin.calificacion.investigacion') &&
                                        $decryptedCurrentProId == $programa->pro_id;
                                @endphp

                                @if ($programa->pro_id != 11)
                                    <li class="{{ $isProgramaActive ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.calificacion.index', ['sede_id' => $sedeencript, 'pro_id' => $proencript]) }}">
                                            <span class="pcoded-micon"><i class="feather icon-menu"></i></span>
                                            <span class="pcoded-mtext">{{ $programa->pro_nombre_abre }}</span>
                                        </a>
                                    </li>

                                    @if (!in_array($programa->pro_id, [8, 9]))
                                        <li class="{{ $isInvestigacionActive ? 'active' : '' }}">
                                            <a
                                                href="{{ route('admin.calificacion.investigacion', ['sede_id' => $sedeencript, 'pro_id' => $proencript]) }}">
                                                <span class="pcoded-micon"><i class="feather icon-menu"></i></span>
                                                <span class="pcoded-mtext">{{ $programa->pro_nombre_abre }} -
                                                    Investigación Especializada</span>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>


                    </li>
                @endforeach
            @endif --}}

            {{-- @if ($usr->can('ajedrez.view') && $hasProId8)
                <div class="pcoded-navigatio-lavel">CAMPEONATO</div>
                <li class="pcoded-hasmenu">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="feather icon-sidebar"></i></span>
                        <span class="pcoded-mtext">Ajedrez</span>
                    </a>
                    <ul class="pcoded-submenu">
                        @foreach ($sedes as $sede)
                            @if ($programas->where('sede_id', $sede->sede_id)->where('pro_id', 8)->count() > 0)
                                <li>
                                    <a
                                        href="{{ route('admin.ajedrez.index', ['sede_id' => encrypt($sede->sede_id)]) }}">
                                        <span class="pcoded-micon"><i class="feather icon-menu"></i></span>
                                        <span class="pcoded-mtext">{{ $sede->sede_nombre }}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>
            @endif --}}
    </div>
</nav>
