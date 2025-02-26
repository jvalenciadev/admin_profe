@extends('backend.layouts.master')

@section('title')
    Solicitudes - Admin Panel
@endsection
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('styles')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('backend/files/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" />

    <link rel="stylesheet" type="text/css"
        href="{{ asset('backend/files/assets/pages/data-table/css/buttons.dataTables.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('backend/files/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" />
    <style>
        .custom-select {
            width: 100%;
            padding: 5px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            color: #495057;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .custom-select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        .custom-select option {
            background-color: #fff;
            color: #495057;
        }

        .custom-select option:checked {
            background-color: #007bff;
            color: white;
        }

        .custom-select:hover {
            border-color: #007bff;
        }

        .sis-turno-text {
            display: block;
            max-width: 350px;
            /* Limitar el ancho máximo si es necesario */
            word-wrap: break-word;
            /* Permitir que el texto se ajuste y haga saltos de línea */
            white-space: normal;
            /* Permitir el salto de línea automáticamente */
        }
        .sis-lugar-text {
            display: block;
            max-width: 200px;
            /* Limitar el ancho máximo si es necesario */
            word-wrap: break-word;
            /* Permitir que el texto se ajuste y haga saltos de línea */
            white-space: normal;
            /* Permitir el salto de línea automáticamente */
        }
    </style>
@endsection


@section('admin-content')
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-header">
                <div class="row align-items-end">
                    <div class="col-lg-8">
                        <div class="page-header-title">
                            <div class="d-inline">
                                <h4>Sedes Solicitadas</h4>
                                <span>Lista de Sedes Solicitadas</span>
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
                                    <a href="#!">Lista de Sedes Solicitadas</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Sedes</h5>
                                <span></span>
                                <br />
                            </div>

                            <div class="card-block">
                                <div class="dt-responsive table-responsive">
                                    <table id="simpletable" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th>Nro</th>
                                                <th>Datos Persona</th>
                                                <th>Lugar</th>
                                                <th>Turno</th>
                                                <th>Estado</th>
                                                <th>Fecha Actualizado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sis as $si)
                                                @php
                                                    // Verificamos si han pasado más de 48 horas desde la última actualización
                                                    $updatedAt = \Carbon\Carbon::parse($si->updated_at);
                                                    // Verificamos si han pasado más de 48 horas y si el estado es "no confirmado"
                                                    $isAlert =
                                                        $updatedAt->diffInHours(\Carbon\Carbon::now()) > 48 &&
                                                        $si->sis_estado == 'no confirmado';
                                                @endphp
                                                <tr class="@if ($isAlert) bg-dark @endif">
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>
                                                        <strong>CI:</strong>
                                                        {{ $si->sis_ci }}<br>{{ $si->sis_nombre_completo }}<br><strong>Cel:</strong>
                                                        {{ $si->sis_celular }}
                                                    </td>
                                                    <td>
                                                        <span class="sis-lugar-text"> {{ $si->sis_departamento }}<br><strong>Lugar:</strong>
                                                        {{ $si->sis_sede }}
                                                        <br><strong>CICLO:
                                                        {{ $si->pro_nombre_abre }}</strong>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="sis-turno-text">{{ $si->sis_turno }}</span>
                                                    </td>
                                                    <td>
                                                        <select class="custom-select estado-selector"
                                                            data-id="{{ $si->sis_id }}"
                                                            data-state="{{ $si->sis_estado }}">
                                                            <option value="no confirmado"
                                                                {{ $si->sis_estado == 'no confirmado' ? 'selected' : '' }}>
                                                                No confirmado
                                                            </option>
                                                            <option value="confirmado"
                                                                {{ $si->sis_estado == 'confirmado' ? 'selected' : '' }}>
                                                                Confirmado
                                                            </option>
                                                            <option value="eliminado"
                                                                {{ $si->sis_estado == 'eliminado' ? 'selected' : '' }}>
                                                                Eliminado
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <!-- Fecha con tamaño de texto pequeño -->
                                                    <td>{{ \Carbon\Carbon::parse($si->updated_at)->translatedFormat('d M \\H\\r\\s. h:i a') }}</td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Nro</th>
                                                <th>Datos Persona</th>
                                                <th>Lugar</th>
                                                <th>Turno</th>
                                                <th>Estado</th>
                                                <th>Fecha Actualizado</th>
                                            </tr>
                                        </tfoot>
                                    </table>
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
    <script src="{{ asset('backend/assets/js/sweetalert2.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.estado-selector').forEach(select => {
                select.addEventListener('change', function() {
                    let sisId = this.getAttribute('data-id');
                    let nuevoEstado = this.value;

                    Swal.fire({
                        title: "¿Estás seguro?",
                        text: "¿Deseas cambiar el estado?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sí, cambiar",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/admin/solicitudes/estado/${sisId}`, {
                                    method: "POST", // Asegurar que sea POST
                                    headers: {
                                        "X-CSRF-TOKEN": document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute(
                                            'content'),
                                        "Content-Type": "application/json"
                                    },
                                    body: JSON.stringify({
                                        sis_estado: nuevoEstado
                                    })
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error(
                                            `Error en la solicitud: ${response.status}`
                                        );
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire("¡Actualizado!",
                                            "El estado ha sido cambiado.", "success"
                                        );
                                    } else {
                                        Swal.fire("Error",
                                            "No se pudo cambiar el estado.", "error"
                                        );
                                    }
                                })
                                .catch(error => {
                                    console.error("Error:", error);
                                    Swal.fire("Error",
                                        "Hubo un problema al actualizar el estado.",
                                        "error");
                                });
                        } else {
                            this.value = this.getAttribute('data-original');
                        }
                    });
                });

                // Guardar el estado original en cada select
                select.setAttribute('data-original', select.value);
            });
        });
    </script>
   
@endsection
