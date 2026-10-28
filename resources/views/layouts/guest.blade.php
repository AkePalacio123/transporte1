<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SIGCT') }} — Acceso</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            margin: 0;
        }

        .login-wrapper {
            min-height: 100vh;
        }

        /* Panel izquierdo: identidad del sistema */
        .login-brand {
            background: linear-gradient(160deg, #1e293b 0%, #000000 100%);
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .login-brand::after {
            content: "";
            position: absolute;
            right: -80px;
            bottom: -80px;
            width: 320px;
            height: 320px;
            background: rgba(13, 110, 253, .12);
            border-radius: 50%;
        }

        .login-brand::before {
            content: "";
            position: absolute;
            left: -60px;
            top: -60px;
            width: 220px;
            height: 220px;
            background: rgba(255, 255, 255, .04);
            border-radius: 50%;
        }

        .login-brand .brand-content {
            position: relative;
            z-index: 2;
        }

        .login-brand .brand-icon {
            font-size: 4rem;
            color: #60a5fa;
        }

        .feature-item {
            color: #cbd5e1;
            font-size: .95rem;
        }

        .feature-item i {
            color: #60a5fa;
        }

        /* Panel derecho: formulario */
        .login-form-panel {
            background: #f8fafc;
        }

        .login-card {
            max-width: 400px;
            width: 100%;
        }

        .btn-login {
            background-color: #1e293b;
            border-color: #1e293b;
            color: #fff;
        }

        .btn-login:hover {
            background-color: #0f172a;
            border-color: #0f172a;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="login-wrapper container-fluid">
        <div class="row login-wrapper">

            {{-- IZQUIERDA: marca --}}
            <div class="col-lg-6 login-brand d-none d-lg-flex align-items-center justify-content-center p-5">
                <div class="brand-content text-center" style="max-width: 420px;">
                    <img src="/transporte1/public/img/logo1.png" alt="Logo" style="max-width: 200px; height: auto;">
                    <h1 class="fw-bold mt-3 mb-1">SIGCT</h1>
                    <p class="mb-4" style="color:#94a3b8;">
                        Sistema Integral de Gestión y Control de Transporte
                    </p>

                    <div class="text-start d-inline-block">
                        <div class="feature-item mb-3">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Control de viajes y unidades
                        </div>
                        <div class="feature-item mb-3">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Combustible y mantenimiento
                        </div>
                        <div class="feature-item mb-3">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Dashboard e indicadores
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Reportes exportables
                        </div>
                    </div>
                </div>
            </div>

            {{-- DERECHA: formulario --}}
            <div class="col-lg-6 login-form-panel d-flex align-items-center justify-content-center p-4">
                <div class="login-card">

                    {{-- Encabezado visible solo en móvil --}}
                    <div class="text-center mb-4 d-lg-none">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" style="max-width: 90px; height: auto;">
                        <h3 class="fw-bold mt-2 mb-0">SIGCT</h3>
                        <small class="text-muted">Gestión y Control de Transporte</small>
                    </div>

                    {{ $slot }}

                </div>
            </div>

        </div>
    </div>
</body>
</html>