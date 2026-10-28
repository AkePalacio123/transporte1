@extends('layouts.app')

@section('title','Dashboard')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-speedometer2 me-2"></i>Panel de control
        </h2>
        @if(auth()->user()?->puedeEditar())
            <a href="{{ route('viajes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Nuevo viaje
            </a>
        @endif
    </div>

    {{-- ===================== ALERTAS DE PAGO ===================== --}}
    @if($alertasPago['vencidos_cantidad'] > 0 || $alertasPago['proximos_cantidad'] > 0)
        <div class="row g-3 mb-3">
 
            {{-- Vencidos (rojo) --}}
            @if($alertasPago['vencidos_cantidad'] > 0)
                <div class="col-lg-6">
                    <div class="alert alert-danger border-0 shadow-sm mb-0 h-100">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-exclamation-octagon-fill fs-3 me-3"></i>
                            <div class="flex-grow-1">
                                <h5 class="alert-heading fw-bold mb-1">
                                    {{ $alertasPago['vencidos_cantidad'] }}
                                    {{ $alertasPago['vencidos_cantidad'] == 1 ? 'pago vencido' : 'pagos vencidos' }}
                                </h5>
                                <p class="mb-2">
                                    Total por cobrar vencido:
                                    <strong>${{ number_format($alertasPago['vencidos_monto'], 2) }}</strong>
                                </p>
 
                                @if($viajesVencidos->count() > 0)
                                    <ul class="mb-0 ps-3 small">
                                        @foreach($viajesVencidos as $vv)
                                            <li>
                                                <a href="{{ route('viajes.show', $vv) }}"
                                                   class="alert-link text-decoration-none">
                                                    {{ $vv->identificador }}
                                                </a>
                                                — {{ $vv->empresa->nombre ?? 'Sin empresa' }},
                                                venció el {{ optional($vv->fecha_limite_pago)->format('d/m/Y') }}
                                                (${{ number_format($vv->pago, 2) }})
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
 
            {{-- Próximos a vencer (amarillo) --}}
            @if($alertasPago['proximos_cantidad'] > 0)
                <div class="col-lg-6">
                    <div class="alert alert-warning border-0 shadow-sm mb-0 h-100">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-clock-history fs-3 me-3"></i>
                            <div class="flex-grow-1">
                                <h5 class="alert-heading fw-bold mb-1">
                                    {{ $alertasPago['proximos_cantidad'] }}
                                    {{ $alertasPago['proximos_cantidad'] == 1 ? 'pago próximo a vencer' : 'pagos próximos a vencer' }}
                                </h5>
                                <p class="mb-0">
                                    Vencen en los próximos {{ $alertasPago['margen_dias'] }} días.
                                    Total: <strong>${{ number_format($alertasPago['proximos_monto'], 2) }}</strong>
                                </p>
                                <a href="{{ route('viajes.index', ['estatus_pago' => 'Pendiente']) }}"
                                   class="alert-link small">
                                    Ver viajes pendientes de pago →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
 
        </div>
    @endif

    {{-- ===================== ALERTA DE DOCUMENTOS ===================== --}}
    @if($alertaDocumentos['vencidos'] > 0 || $alertaDocumentos['por_vencer'] > 0)
        <div class="row g-3 mb-3">
            <div class="col-12">
                <div class="alert alert-{{ $alertaDocumentos['vencidos'] > 0 ? 'danger' : 'warning' }} border-0 shadow-sm mb-0">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-file-earmark-medical-fill fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Documentos de unidades</h6>
                                <span>
                                    @if($alertaDocumentos['vencidos'] > 0)
                                        <strong>{{ $alertaDocumentos['vencidos'] }}</strong> vencido(s).
                                    @endif
                                    @if($alertaDocumentos['por_vencer'] > 0)
                                        <strong>{{ $alertaDocumentos['por_vencer'] }}</strong> próximo(s) a vencer.
                                    @endif
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('alertas.documentos') }}" class="btn btn-sm btn-dark">
                            Ver detalle <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ============ ALERTA DE MANTENIMIENTO POR KM ============ --}}
    @if($tractosMantenimiento > 0)
        <div class="row g-3 mb-3">
            <div class="col-12">
                <div class="alert alert-warning border-0 shadow-sm mb-0">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-speedometer fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Mantenimiento por kilómetros</h6>
                                <span>
                                    <strong>{{ $tractosMantenimiento }}</strong>
                                    tracto(s) requieren mantenimiento pronto.
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('alertas.mantenimiento') }}" class="btn btn-sm btn-dark">
                            Ver detalle <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ===================== KPIs PRINCIPALES ===================== --}}
    <div class="row g-3 mb-3">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small text-uppercase">Viajes totales</div>
                            <div class="fs-2 fw-bold">{{ number_format($kpis['viajes_total']) }}</div>
                            <div class="small text-muted">
                                {{ $kpis['viajes_mes'] }} este mes
                            </div>
                        </div>
                        <i class="bi bi-signpost-split fs-1 text-primary opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small text-uppercase">Cobrado</div>
                            <div class="fs-2 fw-bold text-success">
                                ${{ number_format($kpis['ingresos_cobrados'], 2) }}
                            </div>
                            <div class="small text-muted">Viajes pagados</div>
                        </div>
                        <i class="bi bi-cash-stack fs-1 text-success opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small text-uppercase">Por cobrar</div>
                            <div class="fs-2 fw-bold text-warning">
                                ${{ number_format($kpis['por_cobrar'], 2) }}
                            </div>
                            <div class="small text-muted">
                                {{ $kpis['viajes_pendientes'] }} viaje(s) pendiente(s)
                            </div>
                        </div>
                        <i class="bi bi-hourglass-split fs-1 text-warning opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small text-uppercase">Utilidad estimada</div>
                            <div class="fs-2 fw-bold {{ $kpis['utilidad_estimada'] >= 0 ? 'text-primary' : 'text-danger' }}">
                                ${{ number_format($kpis['utilidad_estimada'], 2) }}
                            </div>
                            <div class="small text-muted">Cobrado − gastos</div>
                        </div>
                        <i class="bi bi-graph-up-arrow fs-1 text-primary opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ===================== KPIs SECUNDARIOS ===================== --}}
    <div class="row g-3 mb-3">

        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="bi bi-cone-striped fs-4 text-secondary"></i>
                    <div class="fw-bold mt-1">${{ number_format($kpis['gasto_casetas'], 2) }}</div>
                    <div class="small text-muted">Casetas</div>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="bi bi-fuel-pump fs-4 text-secondary"></i>
                    <div class="fw-bold mt-1">${{ number_format($kpis['gasto_combustible'], 2) }}</div>
                    <div class="small text-muted">
                        Combustible · {{ number_format($kpis['litros_total'], 0) }} L
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="bi bi-truck fs-4 text-secondary"></i>
                    <div class="fw-bold mt-1">{{ $kpis['viajes_en_ruta'] }}</div>
                    <div class="small text-muted">En ruta</div>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="bi bi-file-earmark-x fs-4 text-secondary"></i>
                    <div class="fw-bold mt-1">{{ $kpis['bitacoras_pendientes'] }}</div>
                    <div class="small text-muted">Bitácoras pendientes</div>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="bi bi-person-badge fs-4 text-secondary"></i>
                    <div class="fw-bold mt-1">{{ $kpis['operadores'] }}</div>
                    <div class="small text-muted">Operadores</div>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="bi bi-truck-front fs-4 text-secondary"></i>
                    <div class="fw-bold mt-1">{{ $kpis['tractos'] }} / {{ $kpis['equipos'] }}</div>
                    <div class="small text-muted">Tractos / Equipos</div>
                </div>
            </div>
        </div>

    </div>

    {{-- ===================== GRÁFICAS ===================== --}}
    <div class="row g-3 mb-3">

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-bar-chart-line me-2"></i>Viajes e ingresos por mes
                </div>
                <div class="card-body">
                    <canvas id="graficaMeses" height="110"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-pie-chart me-2"></i>Viajes por estatus
                </div>
                <div class="card-body">
                    <canvas id="graficaEstatus" height="200"></canvas>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-3">

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-building me-2"></i>Viajes por empresa
                </div>
                <div class="card-body">
                    <canvas id="graficaEmpresas" height="200"></canvas>
                </div>
            </div>
        </div>

        {{-- ===================== ÚLTIMOS VIAJES ===================== --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold">
                        <i class="bi bi-clock-history me-2"></i>Últimos viajes
                    </span>
                    <a href="{{ route('viajes.index') }}" class="btn btn-sm btn-outline-primary">
                        Ver todos
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Folio / C.P.</th>
                                    <th>Empresa</th>
                                    <th>Fecha</th>
                                    <th class="text-end">Monto</th>
                                    <th>Pago</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($ultimosViajes as $viaje)
                                <tr>
                                    <td>
                                        <a href="{{ route('viajes.show', $viaje) }}" class="text-decoration-none">
                                            {{ $viaje->folio ?: $viaje->cataporte }}
                                        </a>
                                    </td>
                                    <td>{{ $viaje->empresa->nombre ?? '—' }}</td>
                                    <td>{{ optional($viaje->fecha_viaje)->format('d/m/Y') }}</td>
                                    <td class="text-end">${{ number_format($viaje->pago, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $viaje->estatus_pago == 'Pagado' ? 'success' : 'warning text-dark' }}">
                                            {{ $viaje->estatus_pago }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">
                                        Aún no hay viajes registrados.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

{{-- ===================== CHART.JS ===================== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Paleta de marca + acentos vivos
    const marca     = '#1e293b';
    const indigo    = '#4f46e5';
    const cyan      = '#06b6d4';
    const esmeralda = '#10b981';
    const ambar     = '#f59e0b';
    const rosa      = '#ec4899';
    const rojo      = '#ef4444';
    const violeta   = '#8b5cf6';
    const gris      = '#94a3b8';

    const gamaBarras = [indigo, cyan, esmeralda, ambar, violeta, rosa];

    // ---------- Viajes e ingresos por mes ----------
    new Chart(document.getElementById('graficaMeses'), {
        data: {
            labels: @json($meses),
            datasets: [
                {
                    type: 'bar',
                    label: 'Viajes',
                    data: @json($mesesViajes),
                    backgroundColor: indigo,
                    borderRadius: 4,
                    yAxisID: 'y'
                },
                {
                    type: 'line',
                    label: 'Ingresos ($)',
                    data: @json($mesesMontos),
                    borderColor: esmeralda,
                    backgroundColor: esmeralda,
                    tension: 0.3,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: { mode: 'index', intersect: false },
            scales: {
                y:  {
                    beginAtZero: true,
                    position: 'left',
                    title: { display: true, text: 'Viajes' },
                    ticks: { precision: 0 }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: { display: true, text: 'Ingresos' },
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });

    // ---------- Viajes por estatus ----------
    new Chart(document.getElementById('graficaEstatus'), {
        type: 'doughnut',
        data: {
            labels: @json($porEstatus->keys()),
            datasets: [{
                data: @json($porEstatus->values()),
                backgroundColor: [gris, cyan, esmeralda, rojo, ambar]
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // ---------- Viajes por empresa ----------
    new Chart(document.getElementById('graficaEmpresas'), {
        type: 'bar',
        data: {
            labels: @json($porEmpresa->map(fn($f) => $f->empresa->nombre ?? 'Sin empresa')),
            datasets: [{
                label: 'Viajes',
                data: @json($porEmpresa->pluck('total')),
                backgroundColor: gamaBarras,
                borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

});
</script>
@endsection