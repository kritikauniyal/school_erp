<!DOCTYPE html>
<html>
<head>
    <title>Register | School ERP</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background: linear-gradient(135deg,#0d6efd,#004aad);
            height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            font-family:'Segoe UI',sans-serif;
        }

        .register-card{
            width:420px;
            border-radius:15px;
            padding:35px;
            box-shadow:0 15px 40px rgba(0,0,0,.2);
            border:none;
        }

        .form-control{
            height:45px;
            border-radius:8px;
        }

        .btn-register{
            height:45px;
            border-radius:8px;
            font-weight:600;
        }
    </style>
</head>

<body>

<div class="card register-card bg-white">

    <h4 class="text-center mb-4 fw-bold">Create Account</h4>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label>Full Name</label>
            <input name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input name="email" type="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input name="password" type="password" class="form-control" required>
        </div>

        <button class="btn btn-success w-100 btn-register">
            REGISTER
        </button>

    </form>

    <div class="text-center mt-4">
        <small>
            Already have account?
            <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">
                Login
            </a>
        </small>
    </div>

</div>

</body>
</html>