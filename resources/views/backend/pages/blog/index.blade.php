@extends('backend.layouts.master')

@section('title')
    Blogs - Admin Panel
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
                                <h4>Blogs</h4>
                                <span>Lista de Blogs existentes</span>
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
                                    <a href="#!">Lista de Blogs</a>
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
                                <h5>Blogs</h5>
                                <span></span>
                                <br />
                                @include('backend.layouts.partials.messages')
                                @if (Auth::guard('admin')->user()->can('blog.create'))
                                    <a class="btn btn-out btn-primary btn-square"
                                        href="{{ route('admin.blog.create') }}">Agregar
                                    </a>
                                @endif
                            </div>

                            <div class="card-block">
                                <div class="dt-responsive table-responsive">
                                    <table id="simpletable" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th>Nro</th>
                                                <th>Nombre</th>
                                                <th>Estado</th>
                                                <th>Fecha Actualizado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($blogs as $blog)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>
                                                        {{ $blog->blog_titulo }}
                                                    </td>
                                                    <td>
                                                        @if (Auth::guard('admin')->user()->can('blog.edit'))
                                                            <a href="{{ route('admin.blog.estado', encrypt($blog->blog_id)) }}"
                                                                class="btn btn-{{ $blog->blog_estado == 'activo' ? 'success' : 'danger' }}">
                                                                {{ $blog->blog_estado }}
                                                            </a>
                                                        @else
                                                            <a href=""
                                                                class="btn btn-{{ $blog->blog_estado == 'activo' ? 'success' : 'danger' }}">
                                                                {{ $blog->blog_estado }}
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>{{ $blog->updated_at }}</td>
                                                    <td>
                                                        @if (Auth::guard('admin')->user()->can('blog.edit'))
                                                            <a href="{{ route('admin.blog.edit', encrypt($blog->blog_id)) }}"
                                                                class="btn btn-outline-warning waves-effect waves-light m-r-20">
                                                                <i class="icofont icofont-edit-alt"></i>
                                                                <!-- Ícono de Font Awesome -->
                                                            </a>
                                                        @endif
                                                        @if (Auth::guard('admin')->user()->can('blog.delete'))
                                                            <button type="button"
                                                                class="btn btn-outline-danger waves-effect waves-light m-r-20 delete-blog"
                                                                data-id="{{ encrypt($blog->blog_id) }}">
                                                                <i class="icofont icofont-ui-delete"></i>
                                                            </button>
                                                        @endif
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Nro</th>
                                                <th>Nombre</th>
                                                <th>Estado</th>
                                                <th>Fecha Actualizado</th>
                                                <th>Acciones</th>
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
        $(document).ready(function () {
            // Cuando se hace clic en el botón de eliminar
            $('.delete-blog').click(function () {
                var blogId = $(this).data('id');
                var url = "{{ route('admin.blog.destroy', ':id') }}";
                url = url.replace(':id', blogId);

                // Muestra SweetAlert2 para confirmar la eliminación
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',  // Incluye el token CSRF para la solicitud
                            },
                            success: function (response) {
                                Swal.fire(
                                    '¡Eliminado!',
                                    'La galería ha sido eliminada.',
                                    'success'
                                );
                                // Recargar la página o eliminar la fila correspondiente
                                location.reload();  // Puedes usar esto para recargar la página
                                // O elimina la fila de la tabla sin recargar toda la p0.ágina
                                // $(this).closest('tr').remove();
                            },
                            error: function (xhr) {
                                Swal.fire(
                                    'Error',
                                    'Ocurrió un error al eliminar la galería.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
