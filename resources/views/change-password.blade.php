@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

	<style>
        .input-group .toggle-password i {
            cursor: pointer;
        }


    </style>
@stop

@section('title', 'Cambiar Contraseña')

@section('content_header')
    <h1>Cambiar Contraseña</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('change-password.update') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="current_password">Contraseña Actual</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
        <div class="form-group">
            <label for="new_password">Nueva Contraseña</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <div class="form-group">
            <label for="new_password_confirmation">Confirmar Nueva Contraseña</label>
            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
        </div>
        <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
    </form>
@stop

@section('js')

    <script>
        // Script para mostrar/ocultar contraseñas con el icono del ojo
        document.querySelectorAll('.toggle-password').forEach((eye) => {
            eye.addEventListener('click', function () {
                let input = eye.parentElement.querySelector('input');
                let icon = eye.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    </script>
@stop
