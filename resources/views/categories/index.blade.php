@extends('adminlte::page')

@section('title', 'Categorías')

@section('css')
        {{-- <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">

    <!-- DataTables Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"> --}}
	<style>
        /* color a las solicitudes de usuarios vetados */
        table.dataTable tbody tr.blacklist{
            background-color: #FFCDD2 !important;
            color: #E57373 !important;
            font-weight: 400;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background:#4cdcca !important;
            color: #02154f !important;
            font-weight: 900 !important;
        }
        .page-link:hover {
            color: #4cdcca !important;
            background: #09134e !important;
        }
        .dataTables_wrapper .dataTables_paginate .page-link:hover{
            color: #4cdcca !important;
        }
        .previous {
            background-color: #ffffff;
        }
        div.dataTables_wrapper div.dataTables_filter input,
        div.dataTables_wrapper div.dataTables_length select{
            line-height: 1.5;
            height: calc(1.8125rem + 2px);
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            border: 1px solid #cad1d7 !important;
        }

        div.dataTables_wrapper div.dataTables_length select {
            height: 30px;
            border: black 2px solid;
            }



    </style>
@stop

@section('content')

<div class="card mt-4">

    <div class="card-body">
        <!-- Botón centrado -->
        <h3 class="text-center">Categorias</h3>
        <div class="d-flex mb-3">
            <button class="btn btn-primary" data-toggle="modal" data-target="#createCategoryModal">Crear Categoría</button>
        </div>
    
        
        <table id="categories-table" class="table table-bordered ">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
        </div>
    </div>
</div>
   

  

    <!-- Modal de Crear Categoría -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1" role="dialog" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCategoryModalLabel">Crear Categoría</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createCategoryForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="categoryName">Nombre</label>
                            <input type="text" class="form-control" id="categoryName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="categoryDescription">Descripción</label>
                            <textarea class="form-control" id="categoryDescription" name="description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Categoría</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Editar Categoría -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Editar Categoría</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editCategoryForm">
                    <div class="modal-body">
                        <input type="hidden" id="editCategoryId">
                        <div class="form-group">
                            <label for="editCategoryName">Nombre</label>
                            <input type="text" class="form-control" id="editCategoryName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="editCategoryDescription">Descripción</label>
                            <textarea class="form-control" id="editCategoryDescription" name="description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

        $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': @json(csrf_token())
                    }
                });

        $(document).ready(function() {
            $('#categories-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('categories.data') }}',
                columns: [
                    { data: 'name', orderable: false, searchable: true  },
                    { data: 'description', orderable: false, searchable: true  },
                    { data: 'action', orderable: false, searchable: false }
                ],
                processing: true,
                serverSide: true,
                language: {
                    processing:     "Procesando...",
                    search:         "",
                    searchPlaceholder: "Buscar",
                    info:           "",
                    lengthMenu:     "Mostrar _MENU_",
                    infoEmpty:      "Vacío",
                    infoFiltered:   "Información refinada",
                    infoPostFix:    "",
                    loadingRecords: "Procesando...",
                    zeroRecords:    "Vacio",
                    emptyTable:     "Vacio",
                    paginate: {
                        first:      "Primero",
                        previous:   "<",
                        next:       ">",
                        last:       "Último"
                    }
                }
            });
        });

        // Función para editar
        function editCategory(id) {
            $.get('/categories/' + id, function(data) {
                $('#editCategoryId').val(data.id);
                $('#editCategoryName').val(data.name);
                $('#editCategoryDescription').val(data.description);
                $('#editCategoryModal').modal('show');
            });
        }

        // Función para eliminar
        function deleteCategory(id) {

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'No podrás revertir esta acción',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                    url: '/categories/' + id,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        Swal.fire(
                            'Eliminado',
                            'Eliminado con exito.',
                            'success'
                        );

                        $('#categories-table').DataTable().ajax.reload(); // Recargar la tabla
                    }
                });
                }
            });

            
        }

        $('#createCategoryForm').submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();
            
            $.ajax({
                url: '{{ route('categories.store') }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#createCategoryModal').modal('hide');
                    $('#categories-table').DataTable().ajax.reload();
                    Swal.fire(
                        'Creado',
                        'La categoria ha sido creado con exito.',
                        'success'
                    );
                    $('#createCategoryForm')[0].reset();
                }
            });
        });

        $('#editCategoryForm').submit(function(e) {
            e.preventDefault(); // Evitar el envío por defecto del formulario
            
            let categorie = $('#editCategoryId').val();
            let formData = $(this).serialize(); // Serializar los datos del formulario

            $.ajax({
                url: '/categories/' + categorie,
                method: 'PUT',
                data: formData,
                success: function(response) {
                    Swal.fire(
                        'Actualizado',
                        'Categoria actualizada correctamente.',
                        'success'
                    );
                    $('#editCategoryModal').modal('hide');
                    $('#categories-table').DataTable().ajax.reload(); // Recargar la tabla
                    
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error',
                        'No se pudo actualizar el producto.',
                        'error'
                    );
                }
            });
        });

    </script>
@stop
