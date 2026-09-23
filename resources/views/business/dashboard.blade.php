@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    .sidebar {
        background-color: var(--primary);
        color: white;
        height: 100vh;
        position: fixed;
        width: 250px;
    }

    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.8);
        padding: 0.8rem 1rem;
    }

    .sidebar .nav-link:hover {
        color: white;
        background-color: rgba(255, 255, 255, 0.1);
    }

    .sidebar .nav-link.active {
        color: white;
        background-color: var(--secondary);
    }

    .main-content {
        margin-left: 250px;
        padding: 20px;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }

        .main-content {
            margin-left: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="row g-0">
    <!-- Sidebar -->
    <div class="col-md-3 col-lg-2">
        <div class="sidebar">
            <div class="p-4 text-center">
                <img src="{{ Auth::user()->business->logo_url ?? 'https://via.placeholder.com/80' }}" class="rounded-circle mb-3" alt="Negocio">
                <h5>{{ Auth::user()->business->name ?? 'Mi Negocio' }}</h5>
                <p class="small text-muted mb-0">Dashboard Administrativo</p>
            </div>
            <hr>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('business.dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('business.calendar') }}">
                        <i class="bi bi-calendar-week me-2"></i> Agenda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-people me-2"></i> Clientes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-scissors me-2"></i> Servicios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-person-badge me-2"></i> Empleados
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-gear me-2"></i> Configuración
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-md-9 col-lg-10">
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Dashboard Overview</h3>
                <div>
                    <button class="btn btn-outline-secondary me-2">
                        <i class="bi bi-download me-1"></i> Exportar
                    </button>
                    <button class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Nueva Cita
                    </button>
                </div>
            </div>

            <!-- Stats -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card card-dashboard h-100">
                        <div class="card-body">
                            <h6 class="text-muted">Citas Hoy</h6>
                            <div class="d-flex justify-content-between align-items-end">
                                <h3>{{ $todayAppointments->count() }}</h3>
                                <i class="bi bi-calendar-check fs-1 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-dashboard h-100">
                        <div class="card-body">
                            <h6 class="text-muted">Ingresos Mes</h6>
                            <div class="d-flex justify-content-between align-items-end">
                                <h3>${{ number_format($monthlyRevenue, 2) }}</h3>
                                <i class="bi bi-currency-dollar fs-1 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-dashboard h-100">
                        <div class="card-body">
                            <h6 class="text-muted">Ocupación Hoy</h6>
                            <div class="d-flex justify-content-between align-items-end">
                                <h3>{{ $occupancyRate }}%</h3>
                                <i class="bi bi-person-check fs-1 text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Appointments -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <h5 class="mb-0">Citas para Hoy</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Hora</th>
                                    <th>Cliente</th>
                                    <th>Servicio</th>
                                    <th>Profesional</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todayAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->start_time->format('H:i') }}</td>
                                    <td>{{ $appointment->client->name }}</td>
                                    <td>{{ $appointment->service->name }}</td>
                                    <td>{{ $appointment->employee->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">Editar</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No hay citas registradas para hoy</td>
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
@endsection
