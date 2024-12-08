@extends('adminlte::page')

@section('title', 'Gestión de Usuarios')

@section('content_header')
    <h1>Gestión de Usuarios</h1>
@stop

@section('content')


<div class="card mt-4">

    <div class="card-body">
        <!-- Botón centrado -->
        <h3 class="text-center">Movimientos</h3>
        <div class="d-flex mb-3">
            <b<button class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">Crear Usuario</button>
        </div>

        <table id="users-table" class="table table-bordered ">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
        </div>
    </div>
</div>


<!-- Modal de Crear Usuario -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Crear Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createUserForm" method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Rol</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="user">Usuario</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Editar Usuario -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="editUserId" name="id">
                    <div class="form-group">
                        <label for="editUserName">Nombre</label>
                        <input type="text" class="form-control" id="editUserName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editUserEmail">Correo Electrónico</label>
                        <input type="email" class="form-control" id="editUserEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="editUserRole">Rol</label>
                        <select class="form-control" id="editUserRole" name="role" required>
                            <!-- Los roles se llenarán dinámicamente desde AJAX -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editUserPassword">Contraseña</label>
                        <input type="password" class="form-control" id="editUserPassword" name="password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>




    
@stop

@section('css')
    <!-- DataTables CSS -->
    <lik rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@stop

@section('js')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>

$.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': @json(csrf_token())
                    }
                })

        $(document).ready(function() {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('users.index') }}',
                columns: [
                    { data: 'name' },
                    { data: 'email' },
                    { data: 'roles' },
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

        $('#createUserForm').submit(function(e) {
    e.preventDefault();  // Prevenir la recarga de la página
    var formData = $(this).serialize();  // Obtener los datos del formulario

    $.ajax({
        url: '/users',  // Ruta para crear el usuario
        method: 'POST',
        data: formData,
        success: function(data) {
            $('#createUserModal').modal('hide');  // Cerrar el modal
            $('#users-table').DataTable().ajax.reload();  // Recargar la tabla de usuarios
            Swal.fire('Éxito', 'Usuario creado correctamente.', 'success');  // Mostrar notificación
        },
        error: function(xhr) {
            Swal.fire('Error', 'Hubo un problema al crear el usuario.', 'error');
        }
    });
});



        function editUser(userId) {
            $.ajax({
                url: '/users/' + userId + '/edit',  // Ruta para obtener los datos del usuario
                method: 'GET',
                success: function(data) {
                    // Llenamos los campos del formulario con los datos
                    $('#editUserId').val(data.user.id);
                    $('#editUserName').val(data.user.name);
                    $('#editUserEmail').val(data.user.email);
                    $('#editUserPassword').val(''); // No mostrar la contraseña por razones de seguridad
                    $('#editUserRole').empty(); // Limpiar las opciones previas
                    data.roles.forEach(function(role) {
                        $('#editUserRole').append(new Option(role.name, role.name));  // Agregar roles dinámicamente
                    });

                    // Mostramos el modal
                    $('#editUserModal').modal('show');
                },
                error: function(xhr) {
                    alert('No se pudieron cargar los datos del usuario.');
                }
            });
        }


        function deleteUser(userId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminarlo',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/users/' + userId,
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire(
                                'Eliminado!',
                                'El usuario ha sido eliminado.',
                                'success'
                            );
                            // Recargar la tabla o eliminar la fila correspondiente
                            $('#users-table').DataTable().ajax.reload();
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'No se pudo eliminar el usuario.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        $('#createUserModal').on('show.bs.modal', function () {
            $.ajax({
                url: '/roles',  // Ruta que devuelve todos los roles disponibles
                method: 'GET',
                success: function(data) {
                    $('#createUserRole').empty();  // Limpiar los roles previos
                    data.forEach(function(role) {
                        $('#createUserRole').append(new Option(role.name, role.name));  // Agregar cada rol al select
                    });
                }
            });
        });

        $('#editUserForm').submit(function(e) {
            e.preventDefault(); // Evitar el envío por defecto del formulario
            
            let user = $('#editUserId').val();
            let formData = $(this).serialize(); // Serializar los datos del formulario

            $.ajax({
                url: '/users/' + user,
                method: 'PUT',
                data: formData,
                success: function(response) {
                    Swal.fire(
                        'Actualizado',
                        'usuario actualizado correctamente.',
                        'success'
                    );
                    $('#editUserModal').modal('hide');
                    $('#users-table').DataTable().ajax.reload(); // Recargar la tabla
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
