@extends('backend.layouts.master')

@section('title')
    Editar Perfil - PROFE
@endsection

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
@endsection


@section('admin-content')
    <div class="page-wrapper">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <div class="d-inline">
                            <h4>Perfil de usuario</h4>
                            <span>
                                Actualiza tu perfil, consulta los comunicados y participa en las actividades programadas para mantenerte informado.
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="page-header-breadcrumb">
                        <ul class="breadcrumb-title">
                            <li class="breadcrumb-item" style="float: left">
                                <a href="{{ route('admin.dashboard') }}">
                                    <i class="feather icon-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item" style="float: left">
                                <a href="#!">Perfil de usuario</a>
                            </li>
                            <li class="breadcrumb-item" style="float: left">
                                <a href="#!">Perfil de usuario</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="cover-profile">
                        <div class="profile-bg-img">
                            <img class="profile-bg-img img-fluid"
                                src="{{ asset('frontend/images/banner_perfil1.jpg') }}" alt="bg-img" />
                            <div class="card-block user-info">
                                <div class="col-md-12">
                                    <div class="media-left">
                                        @if($perfil->imagen)
                                        <a href="{{ asset('storage/perfil/' . $perfil->imagen) }}" class="profile-image">
                                            <img class="user-img img-radius"
                                                src="{{ asset('storage/perfil/' . $perfil->imagen) }}" alt="user-img"
                                                style="max-width: 120px;" />
                                        </a>
                                        @endif
                                    </div>
                                    <div class="media-body row">
                                        <div class="col-lg-12">
                                            <div class="user-title">
                                                <h2>{{ $perfil->nombre }} {{ $perfil->apellidos }}</h2>
                                                <span class="text-white">{{ $perfil->cargo }}</span>
                                            </div>
                                        </div>
                                        {{-- <div>
                                            <div class="pull-right cover-btn">
                                                <button type="button" class="btn btn-primary m-r-10 m-b-5">
                                                    <i class="icofont icofont-plus"></i>
                                                    Seguir
                                                </button>
                                                <button type="button" class="btn btn-primary">
                                                    <i class="icofont icofont-ui-messaging"></i>
                                                    Mensaje
                                                </button>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="tab-header card">
                        <ul class="nav nav-tabs md-tabs tab-timeline" role="tablist" id="mytab">
                            <li class="nav-item"> <!-- Asignar clase col-2 para que haya espacio suficiente -->
                                <a class="nav-link active" data-toggle="tab" href="#personal" role="tab">Información
                                    personal</a>
                                <div class="slide"></div>
                            </li>
                            <li class="nav-item"> <!-- Asignar clase col-2 para que haya espacio suficiente -->
                                <a class="nav-link" data-toggle="tab" href="#binfo" role="tab">Comunicados / Instructivos</a>
                                <div class="slide"></div>
                            </li>
                            <li class="nav-item"> <!-- Asignar clase col-2 para que haya espacio suficiente -->
                                <a class="nav-link" data-toggle="tab" href="#contacts" role="tab">Contactos del
                                    usuario</a>
                                <div class="slide"></div>
                            </li>
                            <li class="nav-item"> <!-- Asignar clase col-2 para que haya espacio suficiente -->
                                <a class="nav-link" data-toggle="tab" href="#review" role="tab">Actividades</a>
                                <div class="slide"></div>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane active" id="personal" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-header-text">Acerca de mí</h5>
                                    <button id="edit-btn" type="button"
                                        class="btn btn-sm btn-primary waves-effect waves-light f-right">
                                        <i class="icofont icofont-edit"></i>
                                    </button>
                                </div>
                                <div class="card-block">
                                    <div class="view-info">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="general-info">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-xl-6">
                                                            <div class="table-responsive">
                                                                <table class="table m-0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <th scope="row">
                                                                                Licenciatura en
                                                                            </th>
                                                                            <td>{{ $perfil->licenciatura }} </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row">
                                                                                Nombre Completo
                                                                            </th>
                                                                            <td>{{ $perfil->nombre }}
                                                                                {{ $perfil->apellidos }}</td>
                                                                        </tr>

                                                                        <tr>
                                                                            <th scope="row">
                                                                                Fecha de nacimiento
                                                                            </th>
                                                                            <td>
                                                                                {{ $perfil->fecha_nacimiento }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row">
                                                                                Género
                                                                            </th>
                                                                            <td>{{ $perfil->genero }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row">
                                                                                Estado civil
                                                                            </th>
                                                                            <td>{{ $perfil->estado_civil }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row">
                                                                                Ubicación
                                                                            </th>
                                                                            <td>{{ $perfil->direccion }}</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 col-xl-6">
                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                    <tbody>
                                                                        <tr>
                                                                            <th scope="row">
                                                                                Correo electrónico</th>
                                                                            <td>
                                                                                <a href="{{ $perfil->direccion }}"><span
                                                                                        class="__cf_email__"
                                                                                        data-cfemail="084c6d6567486d70696578646d266b6765">{{ $perfil->correo }}</span></a>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row">
                                                                                Número de teléfono móvil
                                                                            </th>
                                                                            <td>(591) - {{ $perfil->celular }} </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row">
                                                                                Curriculum
                                                                            </th>
                                                                            <td>
                                                                                @if ($perfil->curriculum)
                                                                                    <a href="{{ asset('storage/perfil/' . $perfil->curriculum) }}"
                                                                                        class="btn btn-primary" download>
                                                                                        Descargar CV
                                                                                    </a>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="edit-info">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <form action="{{ route('admin.perfil.update', $perfil->id) }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="general-info">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <table class="table">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-addon"><i
                                                                                            class="icofont icofont-user"></i></span>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="licenciatura"
                                                                                        name="licenciatura"
                                                                                        placeholder="Licenciatura en ..."
                                                                                        value="{{ $perfil->licenciatura }}" />
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-addon"><i
                                                                                            class="icofont icofont-user"></i></span>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        placeholder="Nombres"
                                                                                        id="nombre"
                                                                                        value="{{ $perfil->nombre }}" />
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-addon"><i
                                                                                            class="icofont icofont-user"></i></span>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        placeholder="Apellidos"
                                                                                        id="apellidos"
                                                                                        value="{{ $perfil->apellidos }}" />
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="form-radio">
                                                                                    <div class="group-add-on">
                                                                                        <div
                                                                                            class="radio radiofill radio-inline">
                                                                                            <label>
                                                                                                <input type="radio"
                                                                                                    name="genero"
                                                                                                    id="genero"
                                                                                                    value="Masculino"
                                                                                                    {{ $perfil->genero == 'Masculino' ? 'checked' : '' }} />
                                                                                                <i class="helper"></i>
                                                                                                Masculino
                                                                                            </label>
                                                                                        </div>
                                                                                        <div
                                                                                            class="radio radiofill radio-inline">
                                                                                            <label>
                                                                                                <input type="radio"
                                                                                                    name="genero"
                                                                                                    id="genero"
                                                                                                    value="Femenino"
                                                                                                    {{ $perfil->genero == 'Femenino' ? 'checked' : '' }} />
                                                                                                <i class="helper"></i>
                                                                                                Femenino
                                                                                            </label>
                                                                                        </div>
                                                                                        <div
                                                                                            class="radio radiofill radio-inline">
                                                                                            <label>
                                                                                                <input type="radio"
                                                                                                    name="genero"
                                                                                                    id="genero"
                                                                                                    value="No prefiero decirlo"
                                                                                                    {{ $perfil->genero == 'No prefiero decirlo' ? 'checked' : '' }} />
                                                                                                <i class="helper"></i> No
                                                                                                prefiero decirlo
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <input id="fecha_nacimiento"
                                                                                    name="fecha_nacimiento"
                                                                                    class="form-control" type="date"
                                                                                    placeholder="Seleccione su fecha de nacimiento"
                                                                                    value="{{ $perfil->fecha_nacimiento }}" />
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <select class="form-control"
                                                                                    name="estado_civil" id="estado_civil">
                                                                                    <option value="">---- Estado
                                                                                        civil ----</option>
                                                                                    <option value="Casado/a"
                                                                                        {{ $perfil->estado_civil == 'Casado/a' ? 'selected' : '' }}>
                                                                                        Casado/a</option>
                                                                                    <option value="Soltero/a"
                                                                                        {{ $perfil->estado_civil == 'Soltero/a' ? 'selected' : '' }}>
                                                                                        Soltero/a</option>
                                                                                    <option value="Divorciado/a"
                                                                                        {{ $perfil->estado_civil == 'Divorciado/a' ? 'selected' : '' }}>
                                                                                        Divorciado/a</option>
                                                                                    <option value="Comprometido/a"
                                                                                        {{ $perfil->estado_civil == 'Comprometido/a' ? 'selected' : '' }}>
                                                                                        Comprometido/a</option>
                                                                                    <option value="Viudo/a"
                                                                                        {{ $perfil->estado_civil == 'Viudo/a' ? 'selected' : '' }}>
                                                                                        Viudo/a</option>
                                                                                    <option value="No prefiero decirlo"
                                                                                        {{ $perfil->estado_civil == 'No prefiero decirlo' ? 'selected' : '' }}>
                                                                                        No prefiero decirlo</option>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-addon"><i
                                                                                            class="icofont icofont-location-pin"></i></span>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        placeholder="Dirección"
                                                                                        id="direccion" name="direccion"
                                                                                        value="{{ $perfil->direccion }}" />
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-addon"><i
                                                                                            class="icofont icofont-email"></i></span>
                                                                                    <input type="email"
                                                                                        class="form-control"
                                                                                        id="correo" name="correo"
                                                                                        placeholder="Ingrese su correo electrónico"
                                                                                        value="{{ $perfil->correo }}" />
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-addon"><i
                                                                                            class="icofont icofont-mobile-phone"></i></span>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="celular" name="celular"
                                                                                        placeholder="Número de teléfono móvil"
                                                                                        value="{{ $perfil->celular }}" />
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-addon"><i
                                                                                            class="icofont icofont-social-facebook"></i></span>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="facebook" name="facebook"
                                                                                        placeholder="Link de su facebook"
                                                                                        value="{{ $perfil->facebook }}" />
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-addon"><i
                                                                                            class="icofont icofont-link-alt"></i></span>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="tiktok" name="tiktok"
                                                                                        placeholder="Link de su tiktok"
                                                                                        value="{{ $perfil->tiktok }}" />
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <div class="col-lg-6">
                                                                <table class="table">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="container">
                                                                                    <p>📸 Subir Imagen (JPG/PNG, Cuadrada,
                                                                                        Máximo 100KB)</p>
                                                                                    <div class="input-group mb-3">
                                                                                        <input type="file"
                                                                                            id="imageInput"
                                                                                            class="form-control"
                                                                                            accept=".jpg, .jpeg, .png"
                                                                                            name="imagen">
                                                                                    </div>
                                                                                    @if ($perfil->imagen)
                                                                                        <div id="imagePreviewContainer"
                                                                                            class="mt-3">
                                                                                            <h5>Vista Previa:</h5>
                                                                                            <img id="imagePreview"
                                                                                                src="{{ asset('storage/perfil/' . $perfil->imagen) }}"
                                                                                                alt="Vista Previa"
                                                                                                class="img-thumbnail"
                                                                                                style="max-width: 200px;">
                                                                                        </div>
                                                                                    @else
                                                                                        <div id="imagePreviewContainer"
                                                                                            class="mt-3" style="display: none;">
                                                                                            <h5>Vista Previa:</h5>
                                                                                            <img id="imagePreview"
                                                                                                src=""
                                                                                                alt="Vista Previa"
                                                                                                class="img-thumbnail">
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="container">
                                                                                    <p>📄 Subir Curriculum (PDF, Máximo
                                                                                        500KB)</p>
                                                                                    <div class="input-group mb-3">
                                                                                        <input type="file"
                                                                                            id="pdfInput"
                                                                                            class="form-control"
                                                                                            accept=".pdf"
                                                                                            name="curriculum">
                                                                                    </div>
                                                                                    @if ($perfil->curriculum)
                                                                                        <div id="pdfPreviewContainer"
                                                                                            class="mt-3">
                                                                                            <h5>Vista Previa del PDF:</h5>
                                                                                            <iframe id="pdfPreview"
                                                                                                style="width:100%; height:300px;"
                                                                                                src="{{ asset('storage/perfil/' . $perfil->curriculum) }}"></iframe>
                                                                                        </div>
                                                                                    @else
                                                                                        <div id="pdfPreviewContainer"
                                                                                            class="mt-3" style="display: none;">
                                                                                            <h5>Vista Previa del PDF:</h5>
                                                                                            <iframe id="pdfPreview"
                                                                                                style="width:100%; height:300px;"
                                                                                                src=""></iframe>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <div class="text-center">
                                                            <button type="submit"
                                                                class="btn btn-primary waves-effect waves-light m-r-20">Guardar</button>
                                                            <a href="#!" id="edit-cancel"
                                                                class="btn btn-default waves-effect">Cancelar</a>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="binfo" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-header-text">
                                        Comunicados / Instructivos
                                    </h5>
                                </div>
                                <div class="card-block">
                                    <div class="row">
                                        @foreach ($comunicados as $comunicado)
                                            <div class="col-md-6">
                                                <div class="card b-l-success business-info services m-b-20">
                                                    <div class="card-header">
                                                        <div class="service-header">
                                                            <h5 class="card-header-text">
                                                                {{ $comunicado->comun_importancia  }}
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="card-block">
                                                        <div class="row">
                                                            <!-- Imagen y Fecha en la misma fila -->
                                                            <div class="col-4">
                                                                <a href="{{ asset('storage/comunicado/' . $comunicado->comun_imagen) }}" target="_blank">
                                                                <img src="{{ asset('storage/comunicado/' . $comunicado->comun_imagen) }}"
                                                                     alt="Image" class="img-fluid mb-3 comunicado-img"
                                                                     data-bs-toggle="modal" data-bs-target="#imageModal"
                                                                     data-src="{{ asset('storage/comunicado/' . $comunicado->comun_imagen) }}"/>
                                                                    </a>
                                                            </div>
                                                            <div class="col-8">
                                                            <div class="column">
                                                                <h5 class="card-title">{{ $comunicado->comun_nombre  }}</h5>
                                                                <p class="text-muted">Fecha: <strong>{{ \Carbon\Carbon::parse($comunicado->updated_at)->translatedFormat('d \d\e F, Y') }}</strong></p>
                                                                <p class="task-detail">
                                                                    {!! $comunicado->comun_descripcion  !!}
                                                                </p>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <!-- Modal -->

                                        {{-- <div class="col-md-6">
                                            <div class="card b-l-danger business-info services">
                                                <div class="card-header">
                                                    <div class="service-header">
                                                        <a href="#">
                                                            <h5 class="card-header-text">
                                                                Dress and Sarees
                                                            </h5>
                                                        </a>
                                                    </div>
                                                    <span class="dropdown-toggle addon-btn text-muted f-right service-btn"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"
                                                        role="tooltip">
                                                    </span>
                                                    <div class="dropdown-menu dropdown-menu-right b-none services-list">
                                                        <a class="dropdown-item" href="#!"><i
                                                                class="icofont icofont-edit"></i>
                                                            Edit</a>
                                                        <a class="dropdown-item" href="#!"><i
                                                                class="icofont icofont-ui-delete"></i>
                                                            Delete</a>
                                                        <a class="dropdown-item" href="#!"><i
                                                                class="icofont icofont-eye-alt"></i>
                                                            View</a>
                                                    </div>
                                                </div>
                                                <div class="card-block">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <p class="task-detail">
                                                                Lorem ipsum dolor sit amet,
                                                                consectet ur adipisicing
                                                                elit,
                                                                sed do eiusmod temp or
                                                                incidi
                                                                dunt ut labore et.Lorem
                                                                ipsum
                                                                dolor sit amet, consecte.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card b-l-info business-info services">
                                                <div class="card-header">
                                                    <div class="service-header">
                                                        <a href="#">
                                                            <h5 class="card-header-text">
                                                                Shivani Auto Port
                                                            </h5>
                                                        </a>
                                                    </div>
                                                    <span class="dropdown-toggle addon-btn text-muted f-right service-btn"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"
                                                        role="tooltip">
                                                    </span>
                                                    <div class="dropdown-menu dropdown-menu-right b-none services-list">
                                                        <a class="dropdown-item" href="#!"><i
                                                                class="icofont icofont-edit"></i>
                                                            Edit</a>
                                                        <a class="dropdown-item" href="#!"><i
                                                                class="icofont icofont-ui-delete"></i>
                                                            Delete</a>
                                                        <a class="dropdown-item" href="#!"><i
                                                                class="icofont icofont-eye-alt"></i>
                                                            View</a>
                                                    </div>
                                                </div>
                                                <div class="card-block">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <p class="task-detail">
                                                                Lorem ipsum dolor sit amet,
                                                                consectet ur adipisicing
                                                                elit,
                                                                sed do eiusmod temp or
                                                                incidi
                                                                dunt ut labore et.Lorem
                                                                ipsum
                                                                dolor sit amet, consecte.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card b-l-warning business-info services">
                                                <div class="card-header">
                                                    <div class="service-header">
                                                        <a href="#">
                                                            <h5 class="card-header-text">
                                                                Hair stylist
                                                            </h5>
                                                        </a>
                                                    </div>
                                                    <span class="dropdown-toggle addon-btn text-muted f-right service-btn"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"
                                                        role="tooltip">
                                                    </span>
                                                    <div class="dropdown-menu dropdown-menu-right b-none services-list">
                                                        <a class="dropdown-item" href="#!"><i
                                                                class="icofont icofont-edit"></i>
                                                            Edit</a>
                                                        <a class="dropdown-item" href="#!"><i
                                                                class="icofont icofont-ui-delete"></i>
                                                            Delete</a>
                                                        <a class="dropdown-item" href="#!"><i
                                                                class="icofont icofont-eye-alt"></i>
                                                            View</a>
                                                    </div>
                                                </div>
                                                <div class="card-block">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <p class="task-detail">
                                                                Lorem ipsum dolor sit amet,
                                                                consectet ur adipisicing
                                                                elit,
                                                                sed do eiusmod temp or
                                                                incidi
                                                                dunt ut labore et.Lorem
                                                                ipsum
                                                                dolor sit amet, consecte.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card b-l-danger business-info services">
                                                <div class="card-header">
                                                    <div class="service-header">
                                                        <a href="#">
                                                            <h5 class="card-header-text">
                                                                BMW India
                                                            </h5>
                                                        </a>
                                                    </div>
                                                    <span class="dropdown-toggle addon-btn text-muted f-right service-btn"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"
                                                        role="tooltip">
                                                    </span>
                                                    <div class="dropdown-menu dropdown-menu-right b-none services-list">
                                                        <a class="dropdown-item" href="#!"><i
                                                                class="icofont icofont-edit"></i>
                                                            Edit</a>
                                                        <a class="dropdown-item" href="#!"><i
                                                                class="icofont icofont-ui-delete"></i>
                                                            Delete</a>
                                                        <a class="dropdown-item" href="#!"><i
                                                                class="icofont icofont-eye-alt"></i>
                                                            View</a>
                                                    </div>
                                                </div>
                                                <div class="card-block">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <p class="task-detail">
                                                                Lorem ipsum dolor sit amet,
                                                                consectet ur adipisicing
                                                                elit,
                                                                sed do eiusmod temp or
                                                                incidi
                                                                dunt ut labore et.Lorem
                                                                ipsum
                                                                dolor sit amet, consecte.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card b-l-success business-info services">
                                                <div class="card-header">
                                                    <div class="service-header">
                                                        <a href="#">
                                                            <h5 class="card-header-text">
                                                                Shivani Hero
                                                            </h5>
                                                        </a>
                                                    </div>
                                                    <span class="dropdown-toggle addon-btn text-muted f-right service-btn"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"
                                                        role="tooltip">
                                                    </span>
                                                    <div class="dropdown-menu dropdown-menu-right b-none services-list">
                                                        <a class="dropdown-item" href="#!"><i
                                                                class="icofont icofont-edit"></i>
                                                            Edit</a>
                                                        <a class="dropdown-item" href="#!"><i
                                                                class="icofont icofont-ui-delete"></i>
                                                            Delete</a>
                                                        <a class="dropdown-item" href="#!"><i
                                                                class="icofont icofont-eye-alt"></i>
                                                            View</a>
                                                    </div>
                                                </div>
                                                <div class="card-block">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <p class="task-detail">
                                                                Lorem ipsum dolor sit amet,
                                                                consectet ur adipisicing
                                                                elit,
                                                                sed do eiusmod temp or
                                                                incidi
                                                                dunt ut labore et.Lorem
                                                                ipsum
                                                                dolor sit amet, consecte.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="tab-pane" id="contacts" role="tabpanel">
                            <div class="row">
                                <div class="col-xl-3">
                                    <div class="card">
                                        <div class="card-header contact-user">
                                            @if ($perfil->imagen)
                                                <img class="img-radius img-40"
                                                    src="{{ asset('storage/perfil/' . $perfil->imagen) }}"
                                                    alt="contact-user">
                                            @else
                                                <!-- No mostrar nada si no existe la imagen -->
                                            @endif

                                            <h5 class="m-l-10">{{ $perfil->nombre }} {{ $perfil->apellidos }}</h5>
                                        </div>
                                        <div class="card-block">
                                            <ul class="list-group list-contacts">
                                                <li class="list-group-item active">
                                                    <a href="#">Todos los contactos</a>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-xl-9">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5 class="card-header-text">
                                                        Contactos
                                                    </h5>
                                                </div>
                                                <div class="card-block contact-details">
                                                    <div class="data_table_main table-responsive dt-responsive">
                                                        <table id="simpletable"
                                                            class="table table-striped table-bordered nowrap">
                                                            <thead>
                                                                <tr>
                                                                    <th>Imagen</th>
                                                                    <th>Nombre Completo</th>
                                                                    <th>Correo</th>
                                                                    <th>Celular</th>
                                                                    <th>Cargo</th>
                                                                    <th>Estado</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($usuarios as $usuario)
                                                                    <tr>
                                                                        <td>
                                                                            @if ($usuario->imagen)
                                                                                <img class="img-radius img-40"
                                                                                    src="{{ asset('storage/perfil/' . $usuario->imagen) }}"
                                                                                    alt="contact-user">
                                                                            @else
                                                                                <!-- No mostrar nada si no existe la imagen -->
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ $usuario->nombre }}
                                                                            {{ $usuario->apellidos }}</td>
                                                                        <td>
                                                                            <a href="mailto:{{ $usuario->correo }}"
                                                                                class="__cf_email__"
                                                                                data-cfemail="c7a6a5a4f6f5f487a0aaa6aeabe9a4a8aa">
                                                                                {{ $usuario->correo }}
                                                                            </a>
                                                                        </td>

                                                                        <td>
                                                                            <a href="https://wa.me/+591{{ substr($usuario->celular, 1) }}"
                                                                                target="_blank">
                                                                                {{ $usuario->celular }}
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                            {{ $usuario->cargo }}
                                                                        </td>
                                                                        <td>
                                                                            @if ($usuario->estado == 'activo')
                                                                                <span
                                                                                    class="badge bg-success text-light">{{ $usuario->estado }}</span>
                                                                            @elseif($usuario->estado == 'inactivo')
                                                                                <span
                                                                                    class="badge bg-secondary text-light">{{ $usuario->estado }}</span>
                                                                            @elseif($usuario->estado == 'suspendido')
                                                                                <span
                                                                                    class="badge bg-warning text-dark">{{ $usuario->estado }}</span>
                                                                            @else
                                                                                <span
                                                                                    class="badge bg-danger text-light">{{ $usuario->estado }}</span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach

                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th>Imagen</th>
                                                                    <th>Nombre Completo</th>
                                                                    <th>Correo</th>
                                                                    <th>Celular</th>
                                                                    <th>Cargo</th>
                                                                    <th>Estado</th>
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

                        <div class="tab-pane" id="review" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-header-text">Activdades</h5>
                                </div>
                                {{-- <div class="card-block">
                                    <ul class="media-list">
                                        <li class="media">
                                            <div class="media-left">
                                                <a href="#">
                                                    <img class="media-object img-radius comment-img"
                                                        src="{{ asset('backend/files/assets/images/avatar-1.jpg') }}"
                                                        alt="Generic placeholder image" />
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                <h6 class="media-heading">
                                                    Sortino media<span class="f-12 text-muted m-l-5">Just
                                                        now</span>
                                                </h6>
                                                <div class="stars-example-css review-star">
                                                    <i class="icofont icofont-star"></i>
                                                    <i class="icofont icofont-star"></i>
                                                    <i class="icofont icofont-star"></i>
                                                    <i class="icofont icofont-star"></i>
                                                    <i class="icofont icofont-star"></i>
                                                </div>
                                                <p class="m-b-0">
                                                    Cras sit amet nibh libero, in gravida
                                                    nulla. Nulla vel metus scelerisque
                                                    ante sollicitudin commodo. Cras purus
                                                    odio, vestibulum in vulputate at,
                                                    tempus viverra turpis.
                                                </p>
                                                <div class="m-b-25">
                                                    <span><a href="#!" class="m-r-10 f-12">Reply</a></span><span><a
                                                            href="#!" class="f-12">Edit</a>
                                                    </span>
                                                </div>
                                                <hr />

                                                <div class="media mt-2">
                                                    <a class="media-left" href="#">
                                                        <img class="media-object img-radius comment-img"
                                                            src="{{ asset('backend/files/assets/images/avatar-2.jpg') }}"
                                                            alt="Generic placeholder image" />
                                                    </a>
                                                    <div class="media-body">
                                                        <h6 class="media-heading">
                                                            Larry heading
                                                            <span class="f-12 text-muted m-l-5">Just
                                                                now</span>
                                                        </h6>
                                                        <div class="stars-example-css review-star">
                                                            <i class="icofont icofont-star"></i>
                                                            <i class="icofont icofont-star"></i>
                                                            <i class="icofont icofont-star"></i>
                                                            <i class="icofont icofont-star"></i>
                                                            <i class="icofont icofont-star"></i>
                                                        </div>
                                                        <p class="m-b-0">
                                                            Cras sit amet nibh libero, in
                                                            gravida nulla. Nulla vel metus
                                                            scelerisque ante sollicitudin
                                                            commodo. Cras purus odio,
                                                            vestibulum in vulputate at,
                                                            tempus
                                                            viverra turpis.
                                                        </p>
                                                        <div class="m-b-25">
                                                            <span><a href="#!"
                                                                    class="m-r-10 f-12">Reply</a></span><span><a
                                                                    href="#!" class="f-12">Edit</a>
                                                            </span>
                                                        </div>
                                                        <hr />

                                                        <div class="media mt-2">
                                                            <div class="media-left">
                                                                <a href="#">
                                                                    <img class="media-object img-radius comment-img"
                                                                        src="{{ asset('backend/files/assets/images/avatar-3.jpg') }}"
                                                                        alt="Generic placeholder image" />
                                                                </a>
                                                            </div>
                                                            <div class="media-body">
                                                                <h6 class="media-heading">
                                                                    Colleen Hurst
                                                                    <span class="f-12 text-muted m-l-5">Just
                                                                        now</span>
                                                                </h6>
                                                                <div class="stars-example-css review-star">
                                                                    <i class="icofont icofont-star"></i>
                                                                    <i class="icofont icofont-star"></i>
                                                                    <i class="icofont icofont-star"></i>
                                                                    <i class="icofont icofont-star"></i>
                                                                    <i class="icofont icofont-star"></i>
                                                                </div>
                                                                <p class="m-b-0">
                                                                    Cras sit amet nibh
                                                                    libero, in
                                                                    gravida nulla. Nulla vel
                                                                    metus
                                                                    scelerisque ante
                                                                    sollicitudin
                                                                    commodo. Cras purus
                                                                    odio,
                                                                    vestibulum in vulputate
                                                                    at,
                                                                    tempus viverra turpis.
                                                                </p>
                                                                <div class="m-b-25">
                                                                    <span><a href="#!"
                                                                            class="m-r-10 f-12">Reply</a></span><span><a
                                                                            href="#!" class="f-12">Edit</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <hr />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="media mt-2">
                                                    <div class="media-left">
                                                        <a href="#">
                                                            <img class="media-object img-radius comment-img"
                                                                src="{{ asset('backend/files/assets/images/avatar-1.jpg') }}"
                                                                alt="Generic placeholder image" />
                                                        </a>
                                                    </div>
                                                    <div class="media-body">
                                                        <h6 class="media-heading">
                                                            Cedric Kelly<span class="f-12 text-muted m-l-5">Just
                                                                now</span>
                                                        </h6>
                                                        <div class="stars-example-css review-star">
                                                            <i class="icofont icofont-star"></i>
                                                            <i class="icofont icofont-star"></i>
                                                            <i class="icofont icofont-star"></i>
                                                            <i class="icofont icofont-star"></i>
                                                            <i class="icofont icofont-star"></i>
                                                        </div>
                                                        <p class="m-b-0">
                                                            Cras sit amet nibh libero, in
                                                            gravida nulla. Nulla vel metus
                                                            scelerisque ante sollicitudin
                                                            commodo. Cras purus odio,
                                                            vestibulum in vulputate at,
                                                            tempus
                                                            viverra turpis.
                                                        </p>
                                                        <div class="m-b-25">
                                                            <span><a href="#!"
                                                                    class="m-r-10 f-12">Reply</a></span><span><a
                                                                    href="#!" class="f-12">Edit</a>
                                                            </span>
                                                        </div>
                                                        <hr />
                                                    </div>
                                                </div>
                                                <div class="media mt-2">
                                                    <a class="media-left" href="#">
                                                        <img class="media-object img-radius comment-img"
                                                            src="{{ asset('backend/files/assets/images/avatar-4.jpg') }}"
                                                            alt="Generic placeholder image" />
                                                    </a>
                                                    <div class="media-body">
                                                        <h6 class="media-heading">
                                                            Larry heading
                                                            <span class="f-12 text-muted m-l-5">Just
                                                                now</span>
                                                        </h6>
                                                        <div class="stars-example-css review-star">
                                                            <i class="icofont icofont-star"></i>
                                                            <i class="icofont icofont-star"></i>
                                                            <i class="icofont icofont-star"></i>
                                                            <i class="icofont icofont-star"></i>
                                                            <i class="icofont icofont-star"></i>
                                                        </div>
                                                        <p class="m-b-0">
                                                            Cras sit amet nibh libero, in
                                                            gravida nulla. Nulla vel metus
                                                            scelerisque ante sollicitudin
                                                            commodo. Cras purus odio,
                                                            vestibulum in vulputate at,
                                                            tempus
                                                            viverra turpis.
                                                        </p>
                                                        <div class="m-b-25">
                                                            <span><a href="#!"
                                                                    class="m-r-10 f-12">Reply</a></span><span><a
                                                                    href="#!" class="f-12">Edit</a>
                                                            </span>
                                                        </div>
                                                        <hr />

                                                        <div class="media mt-2">
                                                            <div class="media-left">
                                                                <a href="#">
                                                                    <img class="media-object img-radius comment-img"
                                                                        src="{{ asset('backend/files/assets/images/avatar-3.jpg') }}"
                                                                        alt="Generic placeholder image" />
                                                                </a>
                                                            </div>
                                                            <div class="media-body">
                                                                <h6 class="media-heading">
                                                                    Colleen Hurst
                                                                    <span class="f-12 text-muted m-l-5">Just
                                                                        now</span>
                                                                </h6>
                                                                <div class="stars-example-css review-star">
                                                                    <i class="icofont icofont-star"></i>
                                                                    <i class="icofont icofont-star"></i>
                                                                    <i class="icofont icofont-star"></i>
                                                                    <i class="icofont icofont-star"></i>
                                                                    <i class="icofont icofont-star"></i>
                                                                </div>
                                                                <p class="m-b-0">
                                                                    Cras sit amet nibh
                                                                    libero, in
                                                                    gravida nulla. Nulla vel
                                                                    metus
                                                                    scelerisque ante
                                                                    sollicitudin
                                                                    commodo. Cras purus
                                                                    odio,
                                                                    vestibulum in vulputate
                                                                    at,
                                                                    tempus viverra turpis.
                                                                </p>
                                                                <div class="m-b-25">
                                                                    <span><a href="#!"
                                                                            class="m-r-10 f-12">Reply</a></span><span><a
                                                                            href="#!" class="f-12">Edit</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <hr />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="media mt-2">
                                                    <div class="media-left">
                                                        <a href="#">
                                                            <img class="media-object img-radius comment-img"
                                                                src="{{ asset('backend/files/assets/images/avatar-2.jpg') }}"
                                                                alt="Generic placeholder image" />
                                                        </a>
                                                    </div>
                                                    <div class="media-body">
                                                        <h6 class="media-heading">
                                                            Mark Doe<span class="f-12 text-muted m-l-5">Just
                                                                now</span>
                                                        </h6>
                                                        <div class="stars-example-css review-star">
                                                            <i class="icofont icofont-star"></i>
                                                            <i class="icofont icofont-star"></i>
                                                            <i class="icofont icofont-star"></i>
                                                            <i class="icofont icofont-star"></i>
                                                            <i class="icofont icofont-star"></i>
                                                        </div>
                                                        <p class="m-b-0">
                                                            Cras sit amet nibh libero, in
                                                            gravida nulla. Nulla vel metus
                                                            scelerisque ante sollicitudin
                                                            commodo. Cras purus odio,
                                                            vestibulum in vulputate at,
                                                            tempus
                                                            viverra turpis.
                                                        </p>
                                                        <div class="m-b-25">
                                                            <span><a href="#!"
                                                                    class="m-r-10 f-12">Reply</a></span><span><a
                                                                    href="#!" class="f-12">Edit</a>
                                                            </span>
                                                        </div>
                                                        <hr />
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Right addon" />
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i
                                                    class="icofont icofont-send-mail"></i></span>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('passwordForm').addEventListener('submit', function(event) {
            const password1 = document.getElementById('password_1').value;
            const password2 = document.getElementById('password_2').value;

            if (password1 !== password2) {
                event.preventDefault(); // Evitar el envío del formulario
                alert("Las contraseñas no coinciden. Por favor, verifícalas.");
            }
        });
    </script>
    <script src="{{ asset('backend/files/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/files/assets/pages/data-table/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/files/assets/pages/user-profile.js') }}"></script>
    <script defer type="text/javascript" src="{{ asset('backend/assets/js/sweetalert2.js') }}"></script>
    <script>
        document.getElementById("imageInput").addEventListener("change", function(event) {
            const file = event.target.files[0];

            if (!file) return;

            // Validar tipo de archivo (JPG/PNG)
            if (!file.type.match('image/jpeg') && !file.type.match('image/png')) {
                Swal.fire("⚠️ Error", "Solo se permiten imágenes JPG o PNG.", "error");
                event.target.value = ""; // Reset input
                return;
            }

            // Validar tamaño máximo (100KB)
            if (file.size > 100 * 1024) {
                Swal.fire("⚠️ Error", "La imagen debe tener máximo 100KB.", "error");
                event.target.value = "";
                return;
            }

            const img = new Image();
            img.src = URL.createObjectURL(file);
            img.onload = function() {
                const width = img.naturalWidth;
                const height = img.naturalHeight;

                // Validar si es cuadrada
                if (width !== height) {
                    Swal.fire("⚠️ Error", "La imagen debe ser cuadrada (ej: 100x100, 200x200, etc.).", "error");
                    event.target.value = "";
                    return;
                }

                // Mostrar la vista previa si no está visible
                const previewContainer = document.getElementById("imagePreviewContainer");
                const previewImage = document.getElementById("imagePreview");

                // Si el contenedor no está visible, mostrarlo
                if (previewContainer.style.display === "none") {
                    previewContainer.style.display = "block";
                }

                // Reemplazar la imagen previa con la nueva imagen seleccionada
                previewImage.src = img.src;
            };
        });

        document.getElementById("pdfInput").addEventListener("change", function(event) {
            const file = event.target.files[0];

            if (!file) return;

            // Validar tipo de archivo (PDF)
            if (!file.type.match('application/pdf')) {
                Swal.fire("⚠️ Error", "Solo se permiten archivos PDF.", "error");
                event.target.value = "";
                return;
            }

            // Validar tamaño mínimo (500KB)
            if (file.size > 500 * 1024) {
                Swal.fire("⚠️ Error", "El PDF debe tener máximo 500KB.", "error");
                event.target.value = "";
                return;
            }

            // Mostrar la vista previa del PDF
            document.getElementById("pdfPreview").src = URL.createObjectURL(file);
            document.getElementById("pdfPreviewContainer").style.display = "block";
        });
    </script>
@endsection
