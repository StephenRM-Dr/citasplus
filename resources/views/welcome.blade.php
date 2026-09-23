@extends('layouts.app')

@section('title', 'Bienvenido a BarberPlus')

@section('content')
<!-- Hero Section -->
<section class="hero-section text-center position-relative overflow-hidden">
    <!-- Decoración de fondo -->
    <div class="hero-glow position-absolute top-0 start-50 translate-middle-x"></div>
    
    <div class="container position-relative py-5">
        <div class="row justify-content-center py-lg-5">
            <div class="col-lg-9 animate-up">
                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2 mb-4 fw-bold">NUEVO: Gestión Inteligente 2.0</span>
                <h1 class="display-3 fw-bold mb-4">
                    {{ config('app.name', 'BarberPlus') }}: Control Total de tu Negocio
                </h1>
                <p class="lead text-muted mb-5 fs-4 px-lg-5">
                    La plataforma definitiva para barberías y spas. Automatiza tus reservas, gestiona tu equipo y enamora a tus clientes con una experiencia digital superior.
                </p>
                <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                    <a href="{{ route('public.booking', 1) }}" class="btn btn-primary btn-lg shadow px-5 py-3 fs-5">
                        <i class="bi bi-calendar-event me-2"></i> Comenzar Ahora
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-5 py-3 fs-5">
                        <i class="bi bi-shop me-2"></i> Registrar mi Negocio
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section (Social Proof) -->
<section class="py-4 border-bottom bg-white">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-4">
                <div class="h3 fw-bold text-primary mb-0">+500</div>
                <p class="text-muted small mb-0">Negocios Activos</p>
            </div>
            <div class="col-md-4">
                <div class="h3 fw-bold text-primary mb-0">10k+</div>
                <p class="text-muted small mb-0">Citas Mensuales</p>
            </div>
            <div class="col-md-4">
                <div class="h3 fw-bold text-primary mb-0">99.9%</div>
                <p class="text-muted small mb-0">Disponibilidad</p>
            </div>
        </div>
    </div>
</section>

<!-- Sección de características -->
<section id="features" class="py-5">
    <div class="container py-lg-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold h1 mb-3">Diseñado para tu Crecimiento</h2>
            <p class="text-muted fs-5">Herramientas potentes y fáciles de usar para elevar tu marca profesional.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card feature-card border-0 shadow-sm p-4 h-100" onclick="window.location='{{ route('public.booking', 1) }}'">
                    <div class="card-body">
                        <div class="icon-box mb-4 bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-calendar-check fs-2"></i>
                        </div>
                        <h4 class="card-title fw-bold mb-3">Reserva Online 24/7</h4>
                        <p class="card-text text-muted mb-4">Un calendario inteligente que permite a tus clientes reservar sin llamadas, incluso mientras duermes.</p>
                        <span class="text-primary fw-bold text-decoration-none">Explorar Demo <i class="bi bi-arrow-right ms-2"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card border-0 shadow-sm p-4 h-100">
                    <div class="card-body">
                        <div class="icon-box mb-4 bg-success bg-opacity-10 text-success">
                            <i class="bi bi-whatsapp fs-2"></i>
                        </div>
                        <h4 class="card-title fw-bold mb-3">Notificaciones Smart</h4>
                        <p class="card-text text-muted mb-4">Recordatorios automáticos por WhatsApp que reducen las inasistencias en un 40% de forma garantizada.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card border-0 shadow-sm p-4 h-100" onclick="window.location='{{ route('login') }}'">
                    <div class="card-body">
                        <div class="icon-box mb-4 bg-info bg-opacity-10 text-info">
                            <i class="bi bi-graph-up-arrow fs-2"></i>
                        </div>
                        <h4 class="card-title fw-bold mb-3">Dashboard Analítico</h4>
                        <p class="card-text text-muted mb-4">Visualiza tus ingresos, rendimiento de empleados y fidelidad de clientes en tiempo real.</p>
                        <span class="text-info fw-bold text-decoration-none">Ver Panel <i class="bi bi-arrow-right ms-2"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container py-lg-5">
        <div class="cta-banner p-5 rounded-5 text-center text-white shadow-lg overflow-hidden position-relative">
            <div class="position-relative z-1">
                <h2 class="display-5 fw-bold mb-3">¿Listo para modernizar tu negocio?</h2>
                <p class="fs-5 mb-4 opacity-75">Únete a cientos de negocios que ya están ahorrando tiempo con BarberPlus.</p>
                <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 py-3 fw-bold text-primary rounded-pill">
                    Prueba Gratis por 14 Días
                </a>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(to bottom, #ffffff 0%, #f1f5f9 100%);
        padding: 5rem 0;
    }
    
    .hero-glow {
        width: 100%;
        max-width: 800px;
        height: 400px;
        background: radial-gradient(circle, rgba(29, 78, 216, 0.05) 0%, rgba(255, 255, 255, 0) 70%);
        filter: blur(50px);
        z-index: 0;
    }

    .feature-card {
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        cursor: pointer;
        border-radius: 24px !important;
    }
    
    .feature-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.06) !important;
        background-color: #fff;
    }

    .icon-box {
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
    }

    .cta-banner {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    }

    .cta-banner::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        top: -100px;
        right: -100px;
    }
</style>
@endpush
@endsection
