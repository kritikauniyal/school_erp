<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | School ERP</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        .left-panel {
            background: linear-gradient(135deg, #0d6efd, #004aad);
            color: white;
        }

        .left-content {
            max-width: 400px;
        }

        .login-card {
            width: 400px;
            border-radius: 15px;
            padding: 35px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            border: none;
        }

        .form-control {
            height: 45px;
            border-radius: 8px;
        }

        .btn-login {
            height: 45px;
            border-radius: 8px;
            font-weight: 600;
        }

        @media(max-width: 991px){
            .left-panel{
                display:none;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid h-100">
    <div class="row h-100">

        <!-- LEFT ERP INFO PANEL -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center left-panel">
            <div class="left-content text-center">
                <h1 class="fw-bold">School ERP System</h1>
                <p class="mt-3">
                    Manage Students, Teachers, Fees & Reports
                    in one powerful integrated platform.
                </p>

                <hr class="bg-light opacity-50">

                <p class="small">
                    Secure • Fast • Professional
                </p>
            </div>
        </div>

        <!-- LOGIN FORM -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center bg-light">

            <div class="card login-card bg-white">

                <h4 class="text-center mb-4 fw-bold">Welcome Back</h4>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input">
                        <label class="form-check-label">Remember Me</label>
                    </div>

                    <button class="btn btn-primary w-100 btn-login">
                        LOGIN
                    </button>

                </form>

                <div class="text-center mt-4">
                    <small>
                        Don't have an account?
                        <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">
                            Register
                        </a>
                    </small>
                </div>

            </div>

        </div>

    </div>
</div>

</body>
</html>