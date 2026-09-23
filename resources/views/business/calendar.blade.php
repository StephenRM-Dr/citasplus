@extends('layouts.app')

@section('title', 'Agenda')

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

    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }
</style>
@endpush

@section('content')
<div class="row g-0">
    <!-- Sidebar -->
    <div class="col-md-3 col-lg-2">
        <div class="sidebar">
            <div class="p-4 text-center">
                @if(Auth::user()->business->logo_url ?? false)
                    <img src="{{ Auth::user()->business->logo_url }}" class="rounded-circle mb-3" alt="Negocio" style="width:80px;height:80px;object-fit:cover;">
                @else
                    <div class="rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center bg-white bg-opacity-25" style="width:80px;height:80px;">
                        <i class="bi bi-shop fs-2"></i>
                    </div>
                @endif
                <h5>{{ Auth::user()->business->name ?? 'Mi Negocio' }}</h5>
                <p class="small text-muted mb-0">Dashboard Administrativo</p>
            </div>
            <hr>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('business.dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('business.calendar') }}">
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
                <h3>Agenda</h3>
                <div class="d-flex gap-3 small text-muted">
                    <span><span class="legend-dot" style="background:#f59e0b"></span>Pendiente</span>
                    <span><span class="legend-dot" style="background:#1d4ed8"></span>Confirmada</span>
                    <span><span class="legend-dot" style="background:#10b981"></span>Completada</span>
                    <span><span class="legend-dot" style="background:#ef4444"></span>Cancelada</span>
                    <span><span class="legend-dot" style="background:#6b7280"></span>No asistió</span>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div id="agendaCalendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('agendaCalendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            locale: 'es',
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: '{{ route('business.calendar.events') }}'
        });
        calendar.render();
    });
</script>
@endpush
