@extends('adminlte::page')

@section('title', 'Movimientos de Productos')

@section('content_header')
    <h1>Movimientos de Productos</h1>
@stop

@section('content')

<div class="card mt-4">

    <div class="card-body">
        <!-- Botón centrado -->
        <h3 class="text-center">Movimientos</h3>
        <div class="d-flex mb-3">
            <button class="btn btn-primary" data-toggle="modal" data-target="#createMovementModal">Registrar Movimiento</button>
        </div>

        <table id="product-movements-table" class="table table-bordered ">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Tipo de Movimiento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
        </div>
    </div>
</div>



    

    <!-- Modal de Crear Movimiento -->
    <div class="modal fade" id="createMovementModal" tabindex="-1" role="dialog" aria-labelledby="createMovementModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createMovementModalLabel">Registrar Movimiento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createMovementForm">

                    <!-- Donde se mostrará el mensaje de error -->
                    <div id="error-message" class="text-center" style="color: red; display: none;"></div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="product_id">Producto</label>
                            <select class="form-control" id="product_id" name="product_id" required>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Cantidad</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                        <div class="form-group">
                            <label for="movement_type">Tipo de Movimiento</label>
                            <select class="form-control" id="movement_type" name="movement_type" required>
                                <option value="entrada">Entrada</option>
                                <option value="salida">Salida</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Movimiento</button>
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
            $('#product-movements-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('product_movements.data') }}',
                columns: [
                    { data: 'product.name', orderable: false, searchable: true },
                    { data: 'quantity', orderable: false, searchable: true },
                    { data: 'movement_type', orderable: false, searchable: true },
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

        // Guardar movimiento
        $('#createMovementForm').submit( async function(e) {
            e.preventDefault();
            $(this).disabled = true;
            await $.ajax({
                url: '{{ route('product_movements.store') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#createMovementModal').modal('hide');
                    $('#product-movements-table').DataTable().ajax.reload();
                    Swal.fire(
                        'Creado',
                        'Movimiento creado con exito.',
                        'success'
                    );
                    $('#createMovementForm')[0].reset(); 
                    document.getElementById('error-message').textContent = '';
                },
                error: function(xhr) {
                    // Comprobar si hay errores y mostrarlos en la vista
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        // Mostrar el error en el frontend
                        document.getElementById('error-message').textContent = xhr.responseJSON.error;
                        document.getElementById('error-message').style.display = 'block';
                    } else {
                        alert('Error al registrar el movimiento.');
                    }
                }
            });
            $(this).disabled = false;
        });

        // Eliminar movimiento
        function deleteMovement(id) {

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
                    url: '/product_movements/' + id,
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
                        $('#product-movements-table').DataTable().ajax.reload();
                    }
                });
                }
            });


        }
    </script>
@stop
