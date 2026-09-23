@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-circle fs-1 text-primary"></i>
                        <h2 class="fw-bold mt-2">Bienvenido de nuevo</h2>
                        <p class="text-muted">Ingresa tus credenciales para acceder</p>
                    </div>
                    
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control form-control-lg bg-light border-0" placeholder="admin@example.com" value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control form-control-lg bg-light border-0" placeholder="********" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">Entrar al Dashboard</button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="mb-0">¿No tienes cuenta? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Regístrate aquí</a></p>
                        <a href="{{ url('/') }}" class="text-muted small text-decoration-none d-block mt-2">Volver al inicio</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
