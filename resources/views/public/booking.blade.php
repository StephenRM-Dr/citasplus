@extends('layouts.app')

@section('title', 'Reserva de Citas')

@push('styles')
<style>
    :root {
        --primary: #10b981;
        --secondary: #059669;
        --accent: #fbbf24;
    }
    .public-header {
        background: linear-gradient(135deg, #09090b 0%, #10b981 100%);
        color: white;
        padding: 60px 0;
        margin-bottom: 30px;
        border-bottom: 1px solid rgba(16, 185, 129, 0.2);
    }
    .service-card:hover {
        border-color: var(--primary) !important;
        background-color: rgba(16, 185, 129, 0.05) !important;
        transform: translateY(-5px);
        transition: all 0.3s ease;
    }
    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
    }
    .btn-primary:hover {
        background-color: var(--secondary);
        border-color: var(--secondary);
    }
    .text-primary {
        color: var(--primary) !important;
    }
    .btn-outline-primary {
        color: var(--primary);
        border-color: var(--primary);
    }
    .btn-outline-primary:hover, .btn-outline-primary.active {
        background-color: var(--primary);
        border-color: var(--primary);
    }
</style>
@endpush

@section('content')
<header class="public-header text-center">
    <div class="container">
        <h2 class="display-6 fw-bold">{{ $business->name }}</h2>
        <p class="lead mb-0">Reserva tu cita online de forma rápida y sencilla</p>
    </div>
</header>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>1. Selecciona un servicio</h5>
                            <div class="row mb-4">
                                @foreach($services as $service)
                                <div class="col-md-6 mb-3">
                                    <div class="card service-card h-100 border-light" onclick="selectService({{ $service->id }}, '{{ $service->name }}', {{ $service->price }}, {{ $service->duration }})">
                                        <div class="card-body text-center">
                                            <i class="bi bi-scissors fs-1 text-primary mb-2"></i>
                                            <h6>{{ $service->name }}</h6>
                                            <p class="text-muted small mb-0">{{ $service->duration }} min · ${{ number_format($service->price, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <h5>2. Selecciona fecha y hora</h5>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div id="bookingCalendar" style="min-height: 400px;"></div>
                                </div>
                            </div>

                            <div id="timeSlotsSection" class="d-none animate-up">
                                <h5>3. Horarios disponibles</h5>
                                <div id="slotsContainer" class="row g-2 mb-4">
                                    <!-- Los slots se cargarán aquí -->
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="sticky-top" style="top: 20px;">
                                <div class="card border-0 shadow-sm bg-light">
                                    <div class="card-header bg-white border-0 pt-3">
                                        <h6 class="mb-0 fw-bold">Resumen de tu cita</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="bookingSummary" class="mb-3">
                                            <p class="text-muted text-center py-3">Selecciona un servicio y horario para continuar</p>
                                        </div>
                                        <hr>
                                        <form id="bookingForm" class="d-none">
                                            @csrf
                                            <input type="hidden" name="service_id" id="service_id">
                                            <input type="hidden" name="employee_id" id="employee_id">
                                            <input type="hidden" name="date" id="date">
                                            <input type="hidden" name="time" id="time">
                                            
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Nombre completo</label>
                                                <input type="text" class="form-control" name="name" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Email</label>
                                                <input type="email" class="form-control" name="email" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Teléfono</label>
                                                <input type="tel" class="form-control" name="phone" required>
                                            </div>
                                            <div class="d-grid mt-4">
                                                <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">Confirmar Reserva</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let selectedService = null;
    let selectedSlot = null;
    let calendar = null;

    function selectService(id, name, price, duration) {
        selectedService = { id, name, price, duration };
        document.getElementById('service_id').value = id;
        
        // Estilo de selección
        document.querySelectorAll('.service-card').forEach(card => {
            card.classList.remove('border-primary', 'bg-light');
        });
        event.currentTarget.classList.add('border-primary', 'bg-light');

        if (document.getElementById('date').value) {
            loadAvailableSlots();
        }
        updateSummary();
    }

    async function loadAvailableSlots() {
        const date = document.getElementById('date').value;
        const serviceId = selectedService.id;
        const container = document.getElementById('slotsContainer');
        const section = document.getElementById('timeSlotsSection');

        container.innerHTML = '<div class="col-12 text-center py-3"><div class="spinner-border text-primary" role="status"></div></div>';
        section.classList.remove('d-none');

        try {
            const response = await fetch(`/api/slots/${ {{ $business->id }} }?date=${date}&service_id=${serviceId}`);
            const slots = await response.json();

            container.innerHTML = '';
            if (slots.length === 0) {
                container.innerHTML = '<div class="col-12 text-center text-muted py-3">No hay horarios disponibles para este día</div>';
                return;
            }

            slots.forEach(slot => {
                const col = document.createElement('div');
                col.className = 'col-4 col-md-3';
                col.innerHTML = `
                    <button type="button" class="btn btn-outline-primary w-100 py-2 slot-btn" 
                            onclick="selectSlot('${slot.time}', '${slot.employee_id}', '${slot.employee_name}')">
                        ${slot.time}
                    </button>
                `;
                container.appendChild(col);
            });
        } catch (error) {
            container.innerHTML = '<div class="col-12 text-center text-danger">Error al cargar horarios</div>';
        }
    }

    function selectSlot(time, employeeId, employeeName) {
        selectedSlot = { 
            time: time, 
            date: document.getElementById('date').value,
            employee_id: employeeId,
            employee_name: employeeName
        };
        
        document.getElementById('time').value = time;
        document.getElementById('employee_id').value = employeeId;

        // Estilo de botones
        document.querySelectorAll('.slot-btn').forEach(btn => {
            btn.classList.remove('active', 'btn-primary');
            btn.classList.add('btn-outline-primary');
        });
        event.target.classList.add('active', 'btn-primary');
        event.target.classList.remove('btn-outline-primary');

        updateSummary();
    }

    function updateSummary() {
        const summary = document.getElementById('bookingSummary');
        const form = document.getElementById('bookingForm');
        
        if (selectedService) {
            let html = `
                <div class="mb-2 d-flex justify-content-between"><span>Servicio:</span> <strong>${selectedService.name}</strong></div>
                <div class="mb-2 d-flex justify-content-between"><span>Precio:</span> <strong>$${selectedService.price}</strong></div>
                <div class="mb-2 d-flex justify-content-between"><span>Duración:</span> <strong>${selectedService.duration} min</strong></div>
            `;
            if (selectedSlot) {
                html += `
                    <div class="mb-2 d-flex justify-content-between bg-primary bg-opacity-10 p-2 rounded">
                        <span>Cita:</span> 
                        <strong>${selectedSlot.date} a las ${selectedSlot.time}</strong>
                    </div>
                `;
                form.classList.remove('d-none');
            } else {
                form.classList.add('d-none');
            }
            summary.innerHTML = html;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('bookingCalendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            selectable: true,
            selectAllow: function(selectInfo) {
                return selectInfo.start >= new Date().setHours(0,0,0,0);
            },
            select: function(info) {
                document.getElementById('date').value = info.startStr;
                selectedSlot = null; // Reiniciar slot al cambiar fecha
                if (selectedService) {
                    loadAvailableSlots();
                } else {
                    alert('Por favor selecciona un servicio primero');
                }
                updateSummary();
            }
        });
        calendar.render();

        document.getElementById('bookingForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = 'Procesando...';

            const formData = new FormData(this);
            try {
                const response = await fetch(`/api/book/${ {{ $business->id }} }`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const result = await response.json();
                if (result.success) {
                    alert('¡Reserva confirmada exitosamente!');
                    window.location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                alert('Hubo un problema al procesar tu reserva.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Confirmar Reserva';
            }
        });
    });
</script>
