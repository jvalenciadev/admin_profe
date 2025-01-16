@extends('backend.layouts.master')

@section('title')
    Eventos - Admin Panel
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('backend/files/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('backend/files/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" />

    <style>
        .opcion {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dot {
            display: inline-block;
            width: 10px;
            /* Ajusta el tamaño del punto */
            height: 10px;
            /* Ajusta el tamaño del punto */
            border-radius: 50%;
            margin-right: 5px;
            /* Espacio entre el punto y el texto */
        }

        .correct {
            background-color: green;
            /* Color verde para correcto */
        }

        .incorrect {
            background-color: red;
            /* Color rojo para incorrecto */
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
                                <h4>Eventos</h4>
                                <span>Cuestionario</span>
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
                                    <a href="#!">Lista de Preguntas</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h4 class="text-primary font-weight-bold">{{ $evento->eve_nombre }}</h4>
                                <div>
                                    @include('backend.layouts.partials.messages')
                                    @if (Auth::guard('admin')->user()->can('eventocuestionario.create') && $numeroDeCuestionario == 0)
                                        <a href="{{ route('admin.eventocuestionario.create', encrypt($evento->eve_id)) }}"
                                            class="btn btn-success btn-sm">
                                            <i class="icofont icofont-plus-circle"></i> Agregar Cuestionario
                                        </a>
                                    @elseif (Auth::guard('admin')->user()->can('eventocuestionario.edit') && $numeroDeCuestionario != 0)
                                        <a href="{{ route('admin.eventocuestionario.edit', encrypt($evento->eve_id)) }}"
                                            class="btn btn-warning btn-sm">
                                            <i class="icofont icofont-edit"></i> Editar Cuestionario
                                        </a>
                                    @endif
                                </div>
                            </div>

                            @if ($numeroDeCuestionario != 0)
                                <div class="card-body">
                                    <div class="mb-4">
                                        <h5 class="text-secondary">{{ $eveCuestionario[0]->eve_cue_titulo }}</h5>
                                        <p class="text-muted">{!! $eveCuestionario[0]->eve_cue_descripcion !!}</p>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p><strong>Fecha de Inicio:</strong>
                                                {{ \Carbon\Carbon::parse($eveCuestionario[0]->eve_cue_fecha_ini)->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p><strong>Fecha de Fin:</strong>
                                                {{ \Carbon\Carbon::parse($eveCuestionario[0]->eve_cue_fecha_fin)->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <a href="{{ route('admin.eventopregunta.create', encrypt($eveCuestionario[0]->eve_cue_id)) }}"
                                            class="btn btn-primary btn-sm">
                                            <i class="icofont icofont-question-circle"></i> Agregar Pregunta
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if (Auth::guard('admin')->user()->can('eventocuestionario.create') && $numeroDeCuestionario != 0)
                            <div class="card mt-3">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="simpletable" class="table table-striped table-bordered">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="width: 5%;">Nro</th>
                                                    <th style="width: 25%;">Pregunta</th>
                                                    <th style="width: 10%;">Tipo</th>
                                                    <th style="width: 10%;">Obligatorio?</th>
                                                    <th style="width: 35%;">Respuestas</th>
                                                    <th style="width: 15%;">Fecha Actualizado</th>
                                                    <th style="width: 10%;">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($evePregunta as $index => $evePre)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $evePre->eve_pre_texto }}<br>{{ $evePre->eve_pre_respuesta_correcta }}
                                                        </td>
                                                        <td>{{ $evePre->eve_pre_tipo }}</td>
                                                        <td>{{ $evePre->eve_pre_obligatorio ? 'Sí' : 'No' }}</td>
                                                        <td>
                                                            @if (Auth::guard('admin')->user()->can('eventocuestionario.create') && $evePre->eve_pre_tipo != "respuesta_abierta")
                                                                <button type="button" class="btn btn-primary btn-sm"
                                                                    data-toggle="modal"
                                                                    data-target="#crearRespuestaModal{{ $evePre->eve_pre_id }}">
                                                                    Crear Respuesta
                                                                </button>
                                                            @endif
                                                            @foreach ($eveOpciones->where('eve_pre_id', $evePre->eve_pre_id) as $index)
                                                                <div class="opcion">
                                                                    <span>
                                                                        <span
                                                                            class="dot {{ $index->eve_opc_es_correcta ? 'correct' : 'incorrect' }}"></span>
                                                                        {{ $index->eve_opc_texto }}
                                                                    </span>

                                                                    <span>
                                                                        <button type="button"
                                                                            class="btn btn-warning btn-sm ml-2"
                                                                            data-toggle="modal"
                                                                            data-target="#editarOpcionModal{{ $index->eve_opc_id }}">
                                                                            <i class="icofont icofont-edit"></i>
                                                                        </button>

                                                                        <form
                                                                            action="{{ route('admin.eventoopciones.destroyopcion', $index->eve_opc_id) }}"
                                                                            method="POST" style="display:inline;">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit"
                                                                                class="btn btn-danger btn-sm ml-2"
                                                                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta opción?');">
                                                                                <i class="icofont icofont-trash"></i>
                                                                            </button>
                                                                        </form>
                                                                    </span>
                                                                </div>
                                                                <!-- Modal para editar opción -->
                                                                <div class="modal fade"
                                                                    id="editarOpcionModal{{ $index->eve_opc_id }}"
                                                                    tabindex="-1" role="dialog"
                                                                    aria-labelledby="editarOpcionModalLabel"
                                                                    aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title"
                                                                                    id="editarOpcionModalLabel">Editar
                                                                                    Opción</h5>
                                                                                <button type="button" class="close"
                                                                                    data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <form
                                                                                action="{{ route('admin.eventoopciones.updateopciones', $index->eve_opc_id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                @method('PUT')
                                                                                <div class="modal-body">
                                                                                    <div class="form-group">
                                                                                        <label for="eve_opc_texto">Texto de
                                                                                            la Opción</label>
                                                                                        <input type="text"
                                                                                            name="eve_opc_texto"
                                                                                            class="form-control" required
                                                                                            value="{{ $index->eve_opc_texto }}">
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            for="eve_opc_es_correcta">¿Es
                                                                                            Correcta?</label>
                                                                                        <select name="eve_opc_es_correcta"
                                                                                            class="form-control">
                                                                                            <option value="1"
                                                                                                {{ $index->eve_opc_es_correcta ? 'selected' : '' }}>
                                                                                                Sí</option>
                                                                                            <option value="0"
                                                                                                {{ !$index->eve_opc_es_correcta ? 'selected' : '' }}>
                                                                                                No</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button"
                                                                                        class="btn btn-secondary"
                                                                                        data-dismiss="modal">Cerrar</button>
                                                                                    <button type="submit"
                                                                                        class="btn btn-primary">Guardar
                                                                                        cambios</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                        <td>{{ $evePre->updated_at->format('Y-m-d H:i:s') }}</td>
                                                        <td>
                                                            @if (Auth::guard('admin')->user()->can('eventocuestionario.edit'))
                                                                <a href="{{ route('admin.eventopregunta.edit', encrypt($evePre->id)) }}"
                                                                    class="btn btn-warning btn-sm">Editar</a>
                                                            @endif
                                                            @if (Auth::guard('admin')->user()->can('eventocuestionario.delete'))
                                                                <form
                                                                    action="{{ route('admin.eventopregunta.destroy', encrypt($evePre->id)) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                                        onclick="return confirm('¿Estás seguro de que deseas eliminar esta pregunta?');">Eliminar</button>
                                                                </form>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <!-- Modal para Crear Respuesta -->
                                                    <div class="modal fade"
                                                        id="crearRespuestaModal{{ $evePre->eve_pre_id }}" tabindex="-1"
                                                        role="dialog" aria-labelledby="crearRespuestaModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="crearRespuestaModalLabel">
                                                                        Crear Respuesta
                                                                    </h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ route('admin.eventoopciones.store') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <!-- Campos para crear la respuesta -->
                                                                        <input type="hidden" name="eve_pre_id"
                                                                            id="eve_pre_id"
                                                                            value="{{ $evePre->eve_pre_id }}">
                                                                        <div class="form-group">
                                                                            <label for="eve_opc_texto">Respuesta</label>
                                                                            <textarea name="eve_opc_texto" id="eve_opc_texto" class="form-control" required></textarea>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="eve_opc_es_correcta">¿Es
                                                                                Correcta?</label>
                                                                            <select name="eve_opc_es_correcta"
                                                                                id="eve_opc_es_correcta"
                                                                                class="form-control">
                                                                                <option value="1">Sí</option>
                                                                                <option value="0">No</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Cerrar</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Guardar</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th style="width: 5%;">Nro</th>
                                                    <th style="width: 25%;">Pregunta</th>
                                                    <th style="width: 10%;">Tipo</th>
                                                    <th style="width: 10%;">Obligatorio?</th>
                                                    <th style="width: 35%;">Respuestas</th>
                                                    <th style="width: 15%;">Fecha Actualizado</th>
                                                    <th style="width: 10%;">Acciones</th>
                                                </tr>
                                            </tfoot>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div id="styleSelector"></div>
@endsection


@section('scripts')
    <!-- Start datatable js -->

    <style>
        .permissions-column {
            width: 60%;
            word-wrap: break-word;
            white-space: normal;
        }
    </style>
    <script src="{{ asset('backend/files/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js') }}">
    </script>
    <script src="{{ asset('backend/files/assets/pages/data-table/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/files/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('backend/files/assets/pages/data-table/js/data-table-custom.js') }}"></script>
    <script>
        /*================================
                                                                                                                            datatable active
                                                                                                                            ==================================*/
    </script>
@endsection
