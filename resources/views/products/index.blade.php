@extends('adminlte::page')


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

    

    </style>
@stop

@section('title', 'Productos')

{{-- @section('content_header')
   
@stop --}}

@section('content')
    <!-- Botón de agregar producto -->
    <div class="card mt-4">

        <div class="card-body">
            <!-- Botón centrado -->
            <h3 class="text-center">Productos</h3>
            <div class="d-flex  mb-3">
                <button class="btn btn-primary" data-toggle="modal" data-target="#createProductModal">Crear Producto</button>
            </div>
        
            <!-- Tabla responsive -->
            <div class="table-responsive">
                <table id="products-table" class="table table-bordered ">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    


    <!-- Modal de creación de producto -->
    <div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createProductModalLabel">Crear Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createProductForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Precio</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" required>
                        </div>
                        <div class="form-group">
                            <label for="category_id">Categoría</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edición -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editProductForm">
                    <div class="modal-body">
                        <input type="hidden" id="editProductId" name="id"> <!-- Campo oculto para el ID -->
                        <div class="form-group">
                            <label for="editProductName">Nombre</label>
                            <input type="text" class="form-control" id="editProductName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="editDescription">Descripción</label>
                            <textarea class="form-control" id="editDescription" name="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editProductPrice">Precio</label>
                            <input type="number" class="form-control" id="editProductPrice" name="price" required>
                        </div>
                        <div class="form-group">
                            <label for="editProductStock">Stock</label>
                            <input type="number" class="form-control" id="editProductStock" name="stock" required>
                        </div>
                        <div class="form-group">
                            <label for="editProductCategory">Categoría</label>
                            <select class="form-control" id="editProductCategory" name="category_id" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
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

        // Asegúrate de incluir el token CSRF en todas las peticiones AJAX
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': @json(csrf_token())
            }
        });

        $(function() {
            // Inicializar DataTable
            var table = $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('products.table') }}',
                columns: [
                    // { data: 'id', orderable: false, searchable: true },
                    { data: 'name', orderable: false, searchable: true },
                    { data: 'description', orderable: false, searchable: true },
                    { data: 'category', orderable: false, searchable: true },
                    { data: 'price', orderable: false, searchable: true },
                    { data: 'stock', orderable: false, searchable: true },
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
                },
                pagingType: 'full_numbers', // Estilo de paginación completo
                
                initComplete: function() {
                    // Opcional: Estilizar los botones adicionales
                    $('ul.pagination').addClass('justify-content-center');
                }
            });
        });

        // Función para eliminar un producto con confirmación
        function deleteProduct(productId) {
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
                        url: '/products/' + productId,
                        method: 'DELETE',
                        success: function(response) {
                            Swal.fire(
                                'Eliminado!',
                                'El producto ha sido eliminado.',
                                'success'
                            );
                            // Actualizar el DataTable después de la eliminación
                            $('#products-table').DataTable().ajax.reload();
                        }
                    });
                }
            });
        }

        async function editProduct(productId) {
            try {
                // Hacer una solicitud AJAX para obtener los datos del producto
                const response = await $.ajax({
                    url: '/products/' + productId,
                    method: 'GET',
                });

                // Log para ver la data que retorna el servidor
                console.log('data editar:', response);

                // Llenar los campos del formulario con los datos del producto
                $('#editProductId').val(response.id); // Campo oculto para ID
                $('#editProductName').val(response.name); // Nombre del producto
                $('#editProductPrice').val(response.price); // Precio del producto
                $('#editDescription').val(response.description); 
                $('#editProductStock').val(response.stock); // Stock del producto
                $('#editProductCategory').val(response.category_id); // Categoría

                // Mostrar el modal después de asegurarte de que los datos se cargaron
                $('#editModal').modal('show');
            } catch (error) {
                console.error(error);
                Swal.fire(
                    'Error',
                    'No se pudieron cargar los datos del producto.',
                    'error'
                );
            }
        }


        $('#editProductForm').submit(function(e) {
            e.preventDefault(); // Evitar el envío por defecto del formulario
            
            let productId = $('#editProductId').val();
            let formData = $(this).serialize(); // Serializar los datos del formulario

            $.ajax({
                url: '/products/' + productId,
                method: 'PUT',
                data: formData,
                success: function(response) {
                    Swal.fire(
                        'Actualizado',
                        'El producto ha sido actualizado correctamente.',
                        'success'
                    );
                    $('#editModal').modal('hide');
                    $('#products-table').DataTable().ajax.reload(); // Recargar la tabla
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

    <script>
        $('#createProductForm').submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();
            
            $.ajax({
                url: '{{ route('products.store') }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#createProductModal').modal('hide');
                    $('#products-table').DataTable().ajax.reload();
                    Swal.fire(
                        'Creado',
                        'El producto ha sido creado con exito.',
                        'success'
                    );
                }
            });
        });
    </script>
@stop
