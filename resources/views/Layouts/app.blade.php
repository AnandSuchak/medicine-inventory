<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Medicine Inventory')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f7fb;
        margin: 0;
        padding: 0;
    }

    footer {
        text-align: center;
        padding: 15px 0;
        background-color: #e9ecef;
        color: #6c757d;
        font-size: 0.9rem;
    }

    header {
        background-color: #00838f;
        position: sticky;
        top: 0;
        z-index: 1000;
        padding: 2px 0; /* reduced padding */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease-in-out;
    }

    header.scrolled {
        background-color: #00bcd4;
        padding: 6px 0; /* smaller when scrolled */
    }

    .navbar-brand {
        font-size: 1.5rem; /* reduced from 2rem */
        font-weight: 600;
        color: white !important;
    }

    .navbar-nav .nav-link {
        font-size: 1.5rem; /* slightly smaller */
        color: #f0f0f0 !important;
        padding: 8px 8px; /* balanced spacing */
    }

    .navbar-nav .nav-link:hover {
        color: #111 !important;
        text-decoration: underline;
    }

    .user-profile img {
        border-radius: 50%;
        width: 30px; /* reduced from 35px */
        height: 30px;
    }

    .dropdown-menu {
        font-size: 0.9rem;
    }

    /* Optional: Make header shrink smoothly on scroll */
    @media (max-width: 768px) {
        .navbar-brand {
            font-size: 1.3rem;
        }

        .navbar-nav .nav-link {
            font-size: 0.95rem;
            padding: 2px 2px;
        }
    }

    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand" href="#">Medicine Inventory</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('batches.index') }}">Batches</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('medicines.index') }}">Medicines</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('stocks.index') }}">Stocks</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('bills.index') }}">Bills</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('suppliers.index') }}">Suppliers</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('customers.index') }}">Customers</a></li>
                    <li class="nav-item dropdown user-profile">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="https://www.w3schools.com/w3images/avatar2.png" alt="User"> John Doe
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Account Settings</a>
                            <a class="dropdown-item" href="#">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-grow-1 py-4 container">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto">
        &copy; {{ date('Y') }} Medicine Inventory. All Rights Reserved.
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        window.onscroll = function () {
            const header = document.querySelector("header");
            if (document.documentElement.scrollTop > 50) {
                header.classList.add("scrolled");
            } else {
                header.classList.remove("scrolled");
            }
        };
    </script>
</body>

</html>
