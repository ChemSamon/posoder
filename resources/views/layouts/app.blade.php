<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | {{ config('app.name', 'Samon Admin') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Battambang&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-bg: #212529; /* Darker than default dark for depth */
            --sidebar-active-color: #0d6efd; /* Bootstrap Primary */
            --body-bg: #f5f5f5; /* Light background */
        }
        
        body {
            background-color: var(--body-bg);
            font-family: 'Battambang', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
            min-height: 100vh;
        }

        .sidebar-wrapper {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            padding-top: 1rem;
            z-index: 1030; /* Above regular content, below modals */
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .main-content-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 0;
        }

        .sidebar a {
            color: rgba(255, 255, 255, 0.8);
            padding: 10px 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            border-radius: 0.3rem;
            margin: 5px 10px;
            transition: background-color 0.2s, color 0.2s;
        }

        .sidebar a:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.08);
        }
        
        .sidebar a.active {
            background-color: var(--sidebar-active-color);
            color: white;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(13, 110, 253, 0.3);
        }
        
        /* Sidebar Logo/Brand Styling */
        .sidebar-brand {
            color: white !important;
            font-size: 1.5rem;
            padding: 20px;
            text-align: center;
            margin-bottom: 10px;
        }
        
        /* Top Header Styling */
        .dashboard-header {
            background-color: #ffffff;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .flag-icon {
            width: 20px;
            height: 14px;
            object-fit: cover;
            margin-right: 8px;
            border: 1px solid #ccc;
        }

        .content-area {
            padding: 20px; /* Reduced top/bottom padding to match header */
        }
        
        /* Logout Link */
        #logout-form a {
            margin-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 15px;
            color: #dc3545 !important;
            font-weight: bold;
        }
        
        /* Hide sidebar on small screens, show toggle */
        @media (max-width: 767.98px) {
            .sidebar-wrapper {
                transform: translateX(-100%);
                /* You would need JS to toggle this class: .sidebar-wrapper.show { transform: translateX(0); } */
            }
            .main-content-wrapper {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <div class="d-flex">
        <div class="sidebar-wrapper d-none d-md-block">
            {{-- <a class="sidebar-brand fw-bolder" href="{{ route('home') }}">
                <i class="bi bi-gear-fill me-2"></i> {{ __('messages.Samon Admin') }}
            </a> --}}
            
            <nav class="sidebar">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="bi bi-house-door me-3"></i> {{ __('messages.Dashboard') }}
                </a>
                <a href="{{ route('pos') }}" class="{{ request()->routeIs('pos') ? 'active' : '' }}">
                    <i class="bi bi-cash-stack me-3"></i> {{ __('messages.POS') }}
                </a>
                <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags-fill me-3"></i> {{ __('messages.Category') }}
                </a>
                <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam-fill me-3"></i> {{ __('messages.Products') }}
                </a>
                <a href="{{ route('orders.history') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history me-3"></i> {{ __('messages.Order History') }}
                </a>
                <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill me-3"></i> {{ __('messages.Users') }}
                </a>
                
                {{-- Logout --}}
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <a href="#" class="text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-3"></i> {{ __('messages.logout') }}
                    </a>
                </form>
            </nav>
        </div>
        <div class="main-content-wrapper flex-grow-1">
            <header class="dashboard-header d-flex justify-content-between align-items-center">
                
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-secondary me-3 d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="bi bi-list"></i>
                    </button>
                    
                    <h5 class="mb-0 text-dark fw-bolder">ចែរញាវបុកល្ហុងកូនកាត់</h5>
                </div>

                <div class="d-flex align-items-center">
                    
                    {{-- <h5 class="mb-0 text-dark fw-normal me-3 d-none d-sm-block" style="font-size: 1rem;">
                        Welcome back, <span class="fw-semibold">{{ Auth::user()->name ?? 'Samon' }}</span>!
                    </h5> --}}
                    
                    <div class="dropdown me-2"> <button class="btn btn-dark dropdown-toggle py-1 px-2" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.9rem;">
                            <i class="bi bi-globe me-1"></i> {{ strtoupper(app()->getLocale()) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="/lang/en">
                                    <img src="https://flagcdn.com/us.svg" class="flag-icon" alt="English Flag"> English
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="/lang/km">
                                    <img src="https://flagcdn.com/kh.svg" class="flag-icon" alt="Khmer Flag"> ខ្មែរ
                                </a>
                            </li>
                             <li>
                                <a class="dropdown-item d-flex align-items-center" href="/lang/ja">
                                    <img src="https://flagcdn.com/jp.svg" class="flag-icon" alt="Japanese Flag"> 日本語
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="/lang/zh">
                                    <img src="https://flagcdn.com/cn.svg" class="flag-icon" alt="Chinese Flag"> 中国
                                </a>
                            </li>
                        </ul>
                    </div>

                    <i class="bi bi-person-circle fs-4 text-primary"></i>
                </div>
            </header>

            <div class="content-area animate__animated animate__fadeIn">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')

</body>
</html>