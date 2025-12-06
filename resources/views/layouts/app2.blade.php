<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Login')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #74ebd5 0%, #ACB6E5 100%);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }

    .form-box {
      background: #fff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
      animation: fadeInUp 1s ease;
    }

    .form-control {
      border-radius: 10px;
    }

    .btn-primary {
      border-radius: 10px;
      transition: 0.3s ease-in-out;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
    }

    @keyframes fadeInUp {
      0% {
        transform: translateY(50px);
        opacity: 0;
      }
      100% {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .register-link {
      text-align: center;
      margin-top: 15px;
    }

    .register-link a {
      color: #0d6efd;
      text-decoration: none;
    }

    .register-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  @yield('content')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
