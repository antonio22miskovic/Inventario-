@extends('adminlte::page')

@section('title', 'Estadísticas')

@section('content_header')
    <h1>Estadísticas de Inventario</h1>
@stop

@section('content')
    <div class="row">
        <!-- Producto más vendido -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Producto Más Vendido</h5>
                </div>
                <div class="card-body">
                    <p>Nombre: {{ $mostSoldProduct->name }}</p>
                    <p>Cantidad Vendida: {{ $mostSoldProduct->movements_count }}</p>
                </div>
            </div>
        </div>

        <!-- Productos con stock bajo -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Productos con Stock Bajo</h5>
                </div>
                <div class="card-body">
                    @foreach ($lowStockProducts as $product)
                        <p>{{ $product->name }} - Stock: {{ $product->stock }}</p>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Últimos movimientos -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Últimos Movimientos</h5>
                </div>
                <div class="card-body">
                    @foreach ($latestMovements as $movement)
                        <p>Producto: {{ $movement->product->name }} - Tipo: {{ $movement->type }} - Fecha: {{ $movement->created_at }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <canvas id="mostSoldChart" width="400" height="200"></canvas>

@stop


@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    var ctx = document.getElementById('mostSoldChart').getContext('2d');
    var mostSoldChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['{{ $mostSoldProduct->name }}'],
            datasets: [{
                label: 'Cantidad Vendida',
                data: [{{ $mostSoldProduct->movements_count }}],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        }
    });
</script>
@stop
<!-- Añadir Chart.js -->
