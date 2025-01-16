@extends('backend.layouts.master')
@php
    use Illuminate\Support\Facades\DB;
    $usr = Auth::guard('admin')->user();
@endphp

@section('title')
    Calificaciones - Admin Panel
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('backend/files/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('backend/files/assets/pages/data-table/css/buttons.dataTables.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('backend/files/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" />
@endsection


@section('admin-content')
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-header">
                <div class="row align-items-end">
                    <div class="col-lg-8">
                        <div class="page-header-title">
                            <div class="d-inline">
                                <h4>Calificaciones</h4>
                                <span>{{ $inscripciones->first()->dep_nombre ?? '' }} -
                                    {{ $inscripciones->first()->sede_nombre ?? 'Nombre de Sede' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="page-header-breadcrumb">
                            <ul class="breadcrumb-title">
                                <li class="breadcrumb-item" style="float: left">
                                    <a href="../index-2.html">
                                        <i class="feather icon-home"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item" style="float: left">
                                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" style="float: left">
                                    <a href="#!">Calificaciones</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-right">
                                <ul class="list-unstyled card-option">
                                    <li><i class="feather icon-maximize full-card"></i></li>
                                    {{-- <li><i class="feather icon-minus minimize-card"></i> --}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-block">

                            <div class="row">
                                <div class="col-lg-12 col-xl-12">
                                    <p>FACILITADOR: {{ $facilitador_nombre }} {{ $facilitador_apellidos }} CEL:
                                        {{ $facilitador_celular }}</p>
                                    <h5>{{ $inscripciones->first()->pro_nombre }}</h5>
                                    <div class="sub-title">{{ $inscripciones->first()->dep_nombre ?? '' }} -
                                        {{ $inscripciones->first()->sede_nombre ?? 'Nombre de Sede' }}
                                    </div>

                                    <!-- El resto de tu contenido aquí -->
                                    @include('backend.layouts.partials.messages')

                                    <ul class="nav nav-tabs tabs" role="tablist" id="calificacionTabs">
                                        @if ($inscripciones->groupBy('pro_tur_id')->count() > 1)
                                            @foreach ($inscripciones->groupBy('pro_tur_id') as $pro_tur_id => $inscripcionesGrouped)
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#tab_{{ $pro_tur_id }}"
                                                        role="tab">
                                                        {{ $inscripcionesGrouped->first()->pro_tur_nombre ?? '' }}
                                                    </a>
                                                    <div class="slide"></div>
                                                </li>
                                            @endforeach
                                        @else
                                            <h6>
                                                <strong>{{ $inscripciones->first()->pro_tur_nombre ?? '' }}</strong>
                                            </h6>
                                        @endif
                                    </ul>
                                    <br>
                                    <div class="tab-content tabs">
                                        @foreach ($inscripciones->groupBy('pro_tur_id') as $pro_tur_id => $inscripcionesGrouped)
                                            <div class="tab-pane {{ $inscripciones->groupBy('pro_tur_id')->count() > 1 ? '' : 'active' }}"
                                                id="tab_{{ $pro_tur_id }}" role="tabpanel">

                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div>
                                                        @php
                                                            $shownModules = [];
                                                        @endphp
                                                        @if ($usr->can('calificacion.reporte'))
                                                            @foreach ($modulos->unique('pm_id') as $modulo)
                                                                <a target="_blank"
                                                                    href="{{ route('admin.calificacion.reportecalificacionpdf', [
                                                                        'sede_id' => $sede_id,
                                                                        'pro_id' => encrypt($modulo->pro_id),
                                                                        'pm_id' => encrypt($modulo->pm_id),
                                                                        'pro_tur_id' => encrypt($pro_tur_id),
                                                                    ]) }}"
                                                                    class="btn btn-outline-danger waves-effect waves-light">
                                                                    <i class="icofont icofont-file-pdf"></i>
                                                                    {{ $modulo->pm_nombre }}
                                                                </a>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="dt-responsive table-responsive">

                                                    <table id="dataTable{{ $loop->index }}"
                                                        class="table table-striped table-bordered nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th>Nro</th>
                                                                <th>CI</th>
                                                                <th>Nombre</th>
                                                                @foreach ($modulos as $modulo)
                                                                    <th style="text-align: center; font-size: 11px; writing-mode: vertical-rl; transform: rotate(180deg);">
                                                                        {{ $modulo->pm_nombre }}<br>
                                                                        <span style="font-size: 8px; display: block;">{{ $modulo->ptc_nombre }}</span>
                                                                        <span style="font-size: 11px;">({{ $modulo->ptc_nota }} ptos.)</span>
                                                                    </th>
                                                                @endforeach
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach ($inscripcionesGrouped as $inscripcion)
                                                                <tr>
                                                                    <td>{{ $loop->index + 1 }}</td>
                                                                    <td>
                                                                        {{ $inscripcion->per_ci }}{{ $inscripcion->per_complemento ? '-' . $inscripcion->per_complemento : '' }}

                                                                    </td>

                                                                    <td>
                                                                        {{ $inscripcion->per_apellido1 }}
                                                                        {{ $inscripcion->per_apellido2 }},
                                                                        {{ $inscripcion->per_nombre1 }}
                                                                        {{ $inscripcion->per_nombre2 }}
                                                                    </td>
                                                                    @php
                                                                        $lock = false; // Variable para determinar si se deben bloquear las celdas
                                                                    @endphp
                                                                    @foreach ($modulos as $modulo)
                                                                        @if ($usr->can('calificacion.edit') || $modulo->pro_id==4)
                                                                            @php
                                                                                $calificacion = \App\Helpers\CalificacionHelper::obtenerCalificacion(
                                                                                    $modulo->pm_id,
                                                                                    $inscripcion->pi_id,
                                                                                    $modulo->pc_id,
                                                                                );
                                                                                $locking = false;
                                                                                // Condición para bloquear las celdas siguientes
                                                                                if ($modulo->pm_estado === 'vista') {
                                                                                    $locking = true;
                                                                                }
                                                                                $backgroundColor = $lock
                                                                                    ? '#d3d3d3'
                                                                                    : match ($modulo->ptc_id) {
                                                                                        3 => '#acffcf',
                                                                                        4
                                                                                            => ($calificacion->cp_puntaje ??
                                                                                            '-') <
                                                                                        70
                                                                                            ? '#f1994b'
                                                                                            : '#4bf190',
                                                                                        default => '#e4fbed',
                                                                                    };
                                                                            @endphp
                                                                            <td style="background-color: {{ $backgroundColor }};"
                                                                                onclick="{{ !$lock && !$locking ? 'makeEditable(this)' : '' }}">

                                                                                @if ($lock)
                                                                                    {{ $calificacion->cp_puntaje ?? '-' }}
                                                                                @else
                                                                                    @if ($modulo->ptc_id != 3 && $modulo->ptc_id != 4)
                                                                                        <span
                                                                                            class="display-mode">{{ $calificacion->cp_puntaje ?? '-' }}</span>
                                                                                        <form
                                                                                            action="{{ route('admin.calificacion.storecalificacion', [
                                                                                                'pi_id' => $inscripcion->pi_id,
                                                                                                'pm_id' => $modulo->pm_id,
                                                                                                'pc_id' => $modulo->pc_id,
                                                                                            ]) }}"
                                                                                            method="POST" class="edit-mode"
                                                                                            style="display:none;"
                                                                                            onsubmit="submitForm(this); return false;">
                                                                                            @csrf
                                                                                            <input
                                                                                                class="form-control input-sm"
                                                                                                type="text"
                                                                                                name="cp_puntaje"
                                                                                                id="cp_puntaje"
                                                                                                value="{{ $calificacion->cp_puntaje ?? '-' }}"
                                                                                                data-max="{{ $modulo->ptc_nota }}"
                                                                                                onblur="exitEditMode(this)"
                                                                                                onkeypress="handleKeyPress(event, this)"
                                                                                                oninput="validateInput(this)">
                                                                                        </form>
                                                                                        @elseif($modulo->ptc_id == 3)
                                                                                            @php
                                                                                                $pc_id_total = $modulos
                                                                                                    ->where('ptc_id', 4)
                                                                                                    ->first();
                                                                                                $calificacionTotal = \App\Helpers\CalificacionHelper::obtenerCalificacion(
                                                                                                    $modulo->pm_id,
                                                                                                    $inscripcion->pi_id,
                                                                                                    $pc_id_total->pc_id,
                                                                                                );
                                                                                                $totalPuntaje =
                                                                                                    $calificacionTotal->cp_puntaje ??
                                                                                                    0;
                                                                                            @endphp
                                                                                            @if ($locking)
                                                                                                {{ $calificacion->cp_puntaje ?? '-' }}
                                                                                            @else
                                                                                                @if ($totalPuntaje < 70 && $totalPuntaje > 39 && $inscripcion->pi_modulo != '1')
                                                                                                    <form
                                                                                                        action="{{ route('admin.calificacion.storecalificacion', [
                                                                                                            'pi_id' => $inscripcion->pi_id,
                                                                                                            'pm_id' => $modulo->pm_id,
                                                                                                            'pc_id' => $modulo->pc_id,
                                                                                                        ]) }}"
                                                                                                        method="POST" s
                                                                                                        tyle="display:inline;">
                                                                                                        @csrf
                                                                                                        <input type="hidden"
                                                                                                            name="cp_puntaje"
                                                                                                            value="70">
                                                                                                        <button type="submit"
                                                                                                            class="btn btn-outline-success waves-effect waves-light"
                                                                                                            onclick="return confirm('¿Estás seguro de que quieres asignar 70 puntos?');">
                                                                                                            Asignar 70 puntos
                                                                                                        </button>
                                                                                                    </form>
                                                                                                @endif
                                                                                            @endif
                                                                                        @elseif($modulo->ptc_id == 4)
                                                                                        {{ $calificacion->cp_puntaje ?? '-' }}
                                                                                    @endif
                                                                                    @php
                                                                                        if($modulo->pro_id!=9){
                                                                                            if (
                                                                                                $modulo->ptc_id === 4 &&
                                                                                                ($calificacion->cp_puntaje ??
                                                                                                    0) <
                                                                                                    70
                                                                                            ) {
                                                                                                $lock = true;
                                                                                            }
                                                                                        }
                                                                                        
                                                                                    @endphp
                                                                                @endif

                                                                            </td>
                                                                        @else
                                                                            @php
                                                                                $calificacion = \App\Helpers\CalificacionHelper::obtenerCalificacion(
                                                                                    $modulo->pm_id,
                                                                                    $inscripcion->pi_id,
                                                                                    $modulo->pc_id,
                                                                                );
                                                                                $locking = false;
                                                                                // Condición para bloquear las celdas siguientes
                                                                                if ($modulo->pm_estado === 'vista') {
                                                                                    $locking = true;
                                                                                }
                                                                                $backgroundColor = $lock
                                                                                    ? '#d3d3d3'
                                                                                    : match ($modulo->ptc_id) {
                                                                                        3 => '#acffcf',
                                                                                        4
                                                                                            => ($calificacion->cp_puntaje ??
                                                                                            '-') <
                                                                                        70
                                                                                            ? '#f1994b'
                                                                                            : '#4bf190',
                                                                                        default => '#e4fbed',
                                                                                    };
                                                                            @endphp
                                                                        
                                                                            <td style="background-color: {{ $backgroundColor }};">
                                                                                {{ $calificacion->cp_puntaje ?? '-' }}
                                                                            </td>
                                                                        @endif
                                                                    @endforeach
                                                                </tr>
                                                            @endforeach
                                                        </tbody>

                                                    </table>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="styleSelector"></div>
@endsection


@section('scripts')
    <script>
        function validateInput(input) {
            const max = parseInt(input.dataset.max);
            const value = parseInt(input.value) || 0;

            if (value > max) {
                alert(`No puedes ingresar más de ${max} puntos.`);
                input.value = max; // Opcional: Ajusta el valor al máximo permitido
            }
        }
        document.addEventListener("DOMContentLoaded", function() {
            const puntajeInputs = document.querySelectorAll('.puntaje-input');
            const totalPuntajeElement = document.getElementById('totalPuntaje');
            const inputTotalPuntaje = document.getElementById('inputTotalPuntaje');
            const asignar70Btn = document.querySelector('.asignar-70-btn');

            puntajeInputs.forEach(input => {
                input.addEventListener('input', function() {
                    calculateTotal();
                });
            });

            if (asignar70Btn) {
                asignar70Btn.addEventListener('click', function() {
                    setTotalPuntaje(70);
                });
            }

            function calculateTotal() {
                let total = 0;
                puntajeInputs.forEach(input => {
                    const ptcId = parseInt(input.dataset.ptcId);
                    const value = parseInt(input.value) || 0;

                    if ([1, 2].includes(ptcId)) {
                        total += value;
                    }
                });

                if (total >= 70) {
                    setTotalPuntaje(total);
                } else if (total === 0) {
                    setTotalPuntaje(0);
                } else {
                    totalPuntajeElement.textContent = total;
                    inputTotalPuntaje.value = total;
                }
            }

            function setTotalPuntaje(value) {
                totalPuntajeElement.textContent = value;
                inputTotalPuntaje.value = value;
                document.getElementById('autoSaveForm_{{ $modulo->pm_id ?? '' }}').submit();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            var tabLinks = document.querySelectorAll('#calificacionTabs .nav-link');
            var activeTabIndex = localStorage.getItem('activeTabIndex');

            if (activeTabIndex !== null && activeTabIndex < tabLinks.length) {
                // Desactiva todas las pestañas y contenidos primero
                tabLinks.forEach(link => {
                    link.classList.remove('active');
                    document.querySelector(link.getAttribute('href')).classList.remove('active', 'show');
                });

                // Activa la pestaña almacenada en localStorage
                tabLinks[activeTabIndex].classList.add('active');
                document.querySelector(tabLinks[activeTabIndex].getAttribute('href')).classList.add('active',
                    'show');
            } else {
                // Si no hay una pestaña almacenada o si el índice es inválido, activa la primera por defecto
                tabLinks[0].classList.add('active');
                document.querySelector(tabLinks[0].getAttribute('href')).classList.add('active', 'show');
            }

            tabLinks.forEach(function(link, index) {
                link.addEventListener('click', function() {
                    // Almacena el índice de la pestaña clickeada
                    localStorage.setItem('activeTabIndex', index);

                    // Desactiva todas las pestañas y contenidos
                    tabLinks.forEach(l => {
                        l.classList.remove('active');
                        document.querySelector(l.getAttribute('href')).classList.remove(
                            'active', 'show');
                    });

                    // Activa la pestaña clickeada y su contenido
                    link.classList.add('active');
                    document.querySelector(link.getAttribute('href')).classList.add('active',
                        'show');
                });
            });
        });

        function makeEditable(td) {
            const span = td.querySelector('.display-mode');
            const form = td.querySelector('.edit-mode');

            span.style.display = 'none';
            form.style.display = 'block';
            form.querySelector('input').focus();
        }

        function exitEditMode(input) {
            const td = input.closest('td');
            const span = td.querySelector('.display-mode');
            const form = td.querySelector('.edit-mode');

            span.style.display = 'block';
            form.style.display = 'none';
        }

        function handleKeyPress(event, input) {
            if (event.key === 'Enter') {
                input.form.submit();
            }
        }

        function submitForm(form) {
            // Aquí puedes manejar la lógica de envío del formulario.
            // Puedes usar AJAX para enviar los datos sin recargar la página.

            const td = form.closest('td');
            const span = td.querySelector('.display-mode');

            // Actualiza el texto del <span> con el nuevo valor
            span.textContent = form.querySelector('input').value;

            exitEditMode(form.querySelector('input'));
        }
    </script>
    <script src="{{ asset('backend/files/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js') }}">
    </script>
    <script src="{{ asset('backend/files/assets/pages/data-table/js/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/files/assets/pages/data-table/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/files/assets/pages/data-table/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>

    <script
        src="{{ asset('backend/files/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}">
    </script>
    <script src="{{ asset('backend/files/assets/pages/data-table/js/data-table-custom.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Inicializar las tablas
            var tables = [];
            @foreach ($inscripciones->groupBy('pro_tur_id') as $pro_tur_id => $inscripcionesGrouped)
                var table{{ $loop->index }} = $('#dataTable{{ $loop->index }}').DataTable({
                    responsive: false,
                    searching: true, // Activa la búsqueda interna de DataTables
                    language: {
                                url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'
                            }
                });
                tables.push(table{{ $loop->index }});
            @endforeach

            // Configurar el buscador general
            $('#searchInput').on('keyup', function() {
                var searchValue = $(this).val().toLowerCase();
                tables.forEach(function(table) {
                    table.columns().every(function() {
                        var column = this;
                        column.search(searchValue, false, true).draw();
                    });
                });
            });
        });
    </script>
@endsection
