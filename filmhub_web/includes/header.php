<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmHub - <?= htmlspecialchars($pageTitle ?? 'Panel') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --orange: #f97316;
            --orange-hover: #ea580c;
            --orange-light: rgba(249, 115, 22, 0.1);
            --dark: #0a0a0a;
            --dark-card: #141414;
            --dark-input: #1e1e1e;
            --dark-sidebar: #111111;
            --border-color: #2a2a2a;
            --text-primary: #f0f0f0;
            --text-secondary: #888;
            --text-muted: #555;
        }

        * { box-sizing: border-box; }

        body {
            background-color: var(--dark);
            color: var(--text-primary);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            margin: 0;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: var(--dark-sidebar);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            z-index: 1050;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 24px 20px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-brand {
            color: var(--orange);
            font-size: 1.4rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .sidebar-close {
            background: none;
            border: none;
            color: #666;
            font-size: 1.2rem;
            cursor: pointer;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 10px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 4px;
        }

        .sidebar-link:hover {
            background: var(--orange-light);
            color: var(--orange);
        }

        .sidebar-link.active {
            background: var(--orange);
            color: #000;
            font-weight: 600;
        }

        .sidebar-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid var(--border-color);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            margin-bottom: 8px;
        }

        .sidebar-user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--orange-light);
            color: var(--orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .sidebar-user-info {
            display: flex;
            flex-direction: column;
        }

        .sidebar-user-name {
            color: var(--text-primary);
            font-size: 0.85rem;
            font-weight: 600;
        }

        .sidebar-user-role {
            color: var(--text-muted);
            font-size: 0.75rem;
        }

        .sidebar-logout {
            color: #ef4444 !important;
        }

        .sidebar-logout:hover {
            background: rgba(239, 68, 68, 0.1) !important;
            color: #ef4444 !important;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 1040;
        }

        .sidebar-overlay.show { display: block; }

        .sidebar-toggle {
            position: fixed;
            top: 16px;
            left: 16px;
            z-index: 1030;
            background: var(--dark-card);
            border: 1px solid var(--border-color);
            color: var(--orange);
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            cursor: pointer;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: 260px;
            padding: 32px;
            min-height: 100vh;
        }

        .page-header {
            margin-bottom: 32px;
        }

        .page-header h2 {
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-primary);
        }

        .page-header p {
            color: var(--text-secondary);
            margin: 4px 0 0;
            font-size: 0.9rem;
        }

        /* ===== CARDS ===== */
        .card-custom {
            background: var(--dark-card);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 24px;
        }

        /* ===== FORMS ===== */
        .form-label { color: #ccc; font-size: 0.85rem; font-weight: 500; }
        .form-control, .form-select {
            background: var(--dark-input);
            border: 1px solid var(--border-color);
            color: #fff;
            border-radius: 10px;
            padding: 10px 14px;
        }
        .form-control:focus, .form-select:focus {
            background: var(--dark-input);
            border-color: var(--orange);
            color: #fff;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
        }
        .form-control::placeholder { color: #555; }
        .form-control:disabled, .form-control[readonly] {
            background: #111;
            color: #666;
            cursor: not-allowed;
        }

        /* ===== BUTTONS ===== */
        .btn-orange {
            background: var(--orange);
            border: none;
            color: #000;
            font-weight: 600;
            border-radius: 10px;
            padding: 10px 20px;
        }
        .btn-orange:hover { background: var(--orange-hover); color: #000; }

        .btn-outline-custom {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            border-radius: 10px;
            padding: 8px 16px;
        }
        .btn-outline-custom:hover { border-color: #555; color: #fff; }

        /* ===== TABLES ===== */
        .table-custom {
            color: var(--text-primary);
        }
        .table-custom thead th {
            background: var(--dark-input);
            color: var(--orange);
            border-bottom: 1px solid var(--border-color);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
        }
        .table-custom tbody td {
            border-bottom: 1px solid var(--border-color);
            padding: 12px 16px;
            vertical-align: middle;
            font-size: 0.9rem;
        }
        .table-custom tbody tr:hover {
            background: rgba(249, 115, 22, 0.04);
        }

        /* ===== BADGES ===== */
        .badge-active {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .badge-inactive {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* ===== ALERTS ===== */
        .alert-success-custom {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
            border-radius: 10px;
            padding: 12px 16px;
        }
        .alert-danger-custom {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            border-radius: 10px;
            padding: 12px 16px;
        }
        .alert-warning-custom {
            background: rgba(249, 115, 22, 0.1);
            border: 1px solid rgba(249, 115, 22, 0.3);
            color: #fdba74;
            border-radius: 10px;
            padding: 12px 16px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                padding: 24px 16px;
                padding-top: 72px;
            }
        }
    </style>
</head>
<body>
