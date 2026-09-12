<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MiniLoan') }}</title>

        <!-- Bootstrap 5 CSS & Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background:
                    radial-gradient(circle at 15% 15%, rgba(59, 130, 246, 0.18), transparent 30%),
                    linear-gradient(135deg, #0f172a 0%, #1e293b 52%, #111827 100%);
                min-height: 100vh;
            }
            .auth-card {
                border: 1px solid rgba(148, 163, 184, 0.3);
                border-radius: 1.5rem;
                background: rgba(255, 255, 255, 0.98);
                box-shadow: 0 1.5rem 3rem rgba(2, 6, 23, 0.28);
            }
            .brand-logo {
                width: 48px;
                height: 48px;
                background: linear-gradient(135deg, #2563eb, #1d4ed8);
                color: #ffffff;
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.35rem;
                box-shadow: 0 0.75rem 1.25rem rgba(37, 99, 235, 0.3);
            }
            .auth-brand {
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.5rem 0.85rem 0.5rem 0.5rem;
                border: 1px solid rgba(147, 197, 253, 0.25);
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.08);
            }
            .auth-form-control {
                min-width: 0;
                padding: 0.55rem 0.75rem;
                font-size: 0.875rem;
                border-color: #dbe3ef;
                transition: border-color 160ms ease, box-shadow 160ms ease, background-color 160ms ease;
            }
            .auth-form-control::placeholder {
                color: #94a3b8;
                font-size: 0.8rem;
            }
            .auth-form-control:focus {
                border-color: #2563eb;
                background-color: #ffffff;
                box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.14);
            }
            .auth-input-group .input-group-text {
                color: #64748b;
                background: #f8fafc;
                border-color: #dbe3ef;
            }
            .auth-input-group:focus-within .input-group-text {
                color: #2563eb;
                border-color: #2563eb;
            }
            .auth-submit {
                transition: transform 160ms ease, box-shadow 160ms ease, background-color 160ms ease;
            }
            .auth-submit:hover {
                transform: translateY(-1px);
                box-shadow: 0 0.65rem 1.2rem rgba(37, 99, 235, 0.25);
            }
        </style>
    </head>
    <body class="d-flex align-items-center justify-content-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
                    
                    <div class="text-center mb-4">
                        <a href="/" class="d-inline-block text-decoration-none">
                            <div class="auth-brand">
                                <div class="brand-logo">
                                <i class="bi bi-bank2"></i>
                                </div>
                                <span class="fw-bold text-white">{{ config('app.name', 'MiniLoan') }}</span>
                            </div>
                        </a>
                    </div>

                    <div class="card auth-card p-4 p-sm-5 rounded-4">
                        {{ $slot }}
                    </div>

                </div>
            </div>
        </div>

        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>