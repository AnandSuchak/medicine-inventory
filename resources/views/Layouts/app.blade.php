<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Medicine Inventory')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <style>
        :root {
            --teal: #00838f;
            --teal-dark: #006064;
            --bg-light: #f7f9fc;
            --text-main: #222;
            --text-light: #555;
            --radius: 12px;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        header {
            background-color: #fff;
            border-bottom: 1px solid #e0e0e0;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--teal) !important;
        }

        .nav-link {
            color: var(--text-light) !important;
            padding: 0.5rem 0.9rem;
            transition: all 0.3s ease;
        }

        .nav-link.active,
        .nav-link:hover {
            background-color: var(--teal);
            color: #fff !important;
            border-radius: var(--radius);
        }

        .user-profile img {
            width: 32px;
            height: 32px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 0.5rem;
        }

        main {
            padding: 2rem 1rem;
        }

        footer {
            background-color: #fff;
            padding: 1rem;
            text-align: center;
            border-top: 1px solid #ddd;
            font-size: 0.875rem;
            color: var(--text-light);
            box-shadow: var(--shadow);
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <header>
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <i class="bi bi-capsule me-2"></i> Medicine Inventory
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNav" aria-controls="navbarNav"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item"><a class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link {{ Request::routeIs('batches.*') ? 'active' : '' }}" href="{{ route('batches.index') }}">Batches</a></li>
                        <li class="nav-item"><a class="nav-link {{ Request::routeIs('medicines.*') ? 'active' : '' }}" href="{{ route('medicines.index') }}">Medicines</a></li>
                        <li class="nav-item"><a class="nav-link {{ Request::routeIs('stocks.*') ? 'active' : '' }}" href="{{ route('stocks.index') }}">Stocks</a></li>
                        <li class="nav-item"><a class="nav-link {{ Request::routeIs('bills.*') ? 'active' : '' }}" href="{{ route('bills.index') }}">Bills</a></li>
                        <li class="nav-item"><a class="nav-link {{ Request::routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">Suppliers</a></li>
                        <li class="nav-item"><a class="nav-link {{ Request::routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">Customers</a></li>
                        <li class="nav-item dropdown user-profile">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://www.w3schools.com/w3images/avatar2.png" alt="User Photo">
                                John Doe
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container flex-grow-1">
        @yield('content')
    </main>

    <footer>
        &copy; {{ date('Y') }} Medicine Inventory. All rights reserved.
    </footer>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@stack('scripts')
</body>
</html> 