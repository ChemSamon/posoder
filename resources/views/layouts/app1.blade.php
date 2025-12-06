<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>POS Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Battambang&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* NEW STYLES FOR MODERN POS LOOK */

        :root {
            --pos-bg: #f5f5f5; /* Light background for the overall app */
            --header-bg: #343a40; /* Dark header/navbar */
            --brand-color: #007bff; /* Primary action color (Blue) */
            --category-bg: #e9ecef; /* Light gray for category buttons */
            --product-card-bg: #ffffff; /* White background for product cards */
            --checkout-bg: #ffffff; /* White background for checkout */
            --red-action: #dc3545;
        }

        body {
            background-color: var(--pos-bg);
            font-family: 'Battambang', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }

        /* --- Header/Navbar --- */
        .navbar {
            background-color: var(--header-bg) !important;
            padding: 0.75rem 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #ffffff !important;
        }
        
        /* === NEW/UPDATED STYLE FOR ADMIN LINK === */
        .sidebar-brand {
            /* Base Button Look */
            padding: 0.5rem 0.75rem; /* py-2 px-3 equivalent */
            border-radius: 20px; /* Matches other buttons in the header */
            background-color: #495057; /* Slightly lighter dark color for contrast */
            text-decoration: none;
            font-size: 1rem; /* Standard button size */
            
            /* Text and Icon Colors */
            color: #ffffff !important;
            transition: all 0.2s ease;
        }

        .sidebar-brand:hover {
            background-color: #6c757d; /* Darker hover */
            color: #ffffff !important;
        }

        .sidebar-brand i {
            color: var(--brand-color); /* Use the primary blue color for the gear icon */
            font-size: 1.1rem;
        }
        /* ======================================= */

        .search-bar {
            background-color: #495057; /* Darker background for search */
            color: #ffffff;
            border-radius: 20px; /* Fully rounded search bar */
            padding: 8px 15px;
            width: 280px;
            border: none;
            transition: all 0.2s;
        }

        .search-bar::placeholder {
            color: #adb5bd;
        }

        .language-btn {
            border-radius: 20px;
            font-weight: 600;
            background-color: #6c757d;
            color: white;
            border: none;
        }

        .logout-btn {
            background-color: var(--red-action);
            color: #fff;
            border-radius: 20px;
            font-weight: 600;
        }
        
        
        /* --- Product Section --- */
        .product-card {
            background: var(--product-card-bg); /* Use solid background */
            border-radius: 12px; /* Smoother corners */
            padding: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05); /* Soft shadow for lift */
            margin-bottom: 12px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .product-card button {
            all: unset; /* Remove default button styles */
            display: block;
            width: 100%;
            height: 100%;
            padding: 0;
            text-align: center;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .product-card img {
            width: 100%;
            height: 110px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 5px;
            border: 1px solid #dee2e6;
        }

        .product-card .fw-bold {
            color: #343a40 !important; /* Dark text for name */
            font-size: 0.9rem;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-card .text-muted {
            color: var(--brand-color) !important; /* Price highlight */
            font-weight: bold;
            font-size: 0.85rem;
        }
        
        /* Category Buttons */
        .category-btns .btn {
            background-color: var(--category-bg);
            color: #495057;
            font-weight: 600;
            border: 1px solid #ced4da;
            border-radius: 20px; /* Pill-shaped buttons */
            transition: all 0.2s;
        }

        .category-btns .btn:hover {
            background-color: #dee2e6;
        }

        .category-btns .btn-danger,
        .category-btns .btn-danger:hover {
            background-color: var(--brand-color); /* Use primary color for active */
            color: white;
            border-color: var(--brand-color);
        }

        /* --- Checkout Section --- */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .card-header {
            background-color: var(--brand-color) !important;
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
            border-top-left-radius: 15px !important;
            border-top-right-radius: 15px !important;
        }

        .list-group-item {
            border-radius: 8px;
            margin-bottom: 8px;
            border: 1px solid #e9ecef;
            padding: 10px;
            background-color: #ffffff;
        }

        .cart-controls input {
            width: 60px;
            text-align: center;
            border-radius: 5px;
        }

        .item-note {
            margin-top: 5px;
            background-color: #f8f9fa;
            border: 1px dashed #ced4da;
            font-size: 0.8rem;
        }

        #totalDisplay {
            padding: 10px;
            background-color: var(--brand-color);
            color: white;
            border-radius: 8px;
            text-align: center;
            margin-top: 15px !important;
        }

        .print-btn, .clear-btn {
            font-weight: bold;
            border-radius: 8px;
            padding: 10px;
        }
        
        .btn-primary {
            background-color: var(--brand-color);
            border-color: var(--brand-color);
        }

        .btn-outline-danger {
            color: var(--red-action);
            border-color: var(--red-action);
        }

        /* Responsive Overrides */
        @media (max-width: 1024px) {
             .product-card .fw-bold {
                 font-size: 0.8rem;
             }
        }
        @media (max-width: 768px) {
            .search-bar { display: none; }
            .col-6 { width: 50%; } /* Ensure 2 columns on small screens */
            .product-card { padding: 5px; }
            .product-card img { height: 90px; }
            .product-card .fw-bold { font-size: 0.75rem; }
        }
    </style>
</head>
<body>
    <nav class="d-flex flex-wrap justify-content-between align-items-center px-4 navbar">
        <div class="d-flex align-items-center">
            <span class="navbar-brand">POS DASHBOARD</span>
            <input type="text" placeholder="Search product..." class="search-bar d-none d-md-block" onkeyup="filterProducts()">
            
        </div>
        <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
            <div>
                <a class="sidebar-brand fw-bolder d-flex align-items-center" href="{{ route('home') }}">
                    <i class="bi bi-gear-fill me-2"></i> {{ __('messages.Admin') }}
                </a>
            </div>

            <div class="dropdown">
                <button class="btn btn-sm dropdown-toggle language-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    🌐 {{ __('messages.Language') }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ url('lang/en') }}">English</a></li>
                    <li><a class="dropdown-item" href="{{ url('lang/km') }}">ខ្មែរ</a></li>
                    <li><a class="dropdown-item" href="{{ url('lang/ja') }}">日本語</a></li>
                    <li><a class="dropdown-item" href="{{ url('lang/zh') }}">中国</a></li>
                </ul>
            </div>

            <a href="#" 
            class="btn btn-sm logout-btn"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            {{ __('messages.Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>

    </nav>

    <div class="container-fluid">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.category-btns button').forEach(button => {
            button.addEventListener('click', function () {
                document.querySelectorAll('.category-btns button').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            });
        });
        
        // Placeholder for filterProducts function since it was referenced in HTML
        function filterProducts() {
            // Your product filtering logic would go here
            console.log("Filtering products...");
        }
    </script>

    @yield('scripts')
</body>
</html>