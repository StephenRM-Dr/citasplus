@extends('layouts.app')

@section('title', 'Registro de Negocio')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-briefcase fs-1 text-primary"></i>
                        <h2 class="fw-bold mt-2">Crea tu cuenta empresarial</h2>
                        <p class="text-muted">Empieza a gestionar tus citas en minutos</p>
                    </div>
                    
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-sm fw-bold">Nombre Completo</label>
                            <input type="text" name="name" class="form-control form-control-lg bg-light border-0" placeholder="Ej: Juan Pérez" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-sm fw-bold">Nombre del Negocio</label>
                            <input type="text" name="business_name" class="form-control form-control-lg bg-light border-0" placeholder="Ej: Barbería Premium" value="{{ old('business_name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-sm fw-bold">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control form-control-lg bg-light border-0" placeholder="admin@example.com" value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-sm fw-bold">Contraseña</label>
                            <input type="password" name="password" class="form-control form-control-lg bg-light border-0" placeholder="********" required>
                        </div>
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">Registrarse</button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="mb-0 text-sm">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Inicia Sesión</a></p>
                        <a href="{{ url('/') }}" class="text-muted small text-decoration-none d-block mt-2">Volver al inicio</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
