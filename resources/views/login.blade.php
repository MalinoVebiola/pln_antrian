<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <script src="{{ asset('assets/fontawesome/all.min.js') }}"></script>
</head>

<body class="bg-greenish">
    <div class="container w-25 text-center m-auto vh-100">
        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->has('loginError'))
            <div class="alert alert-danger mt-3">
                {{ $errors->first('loginError') }}
            </div>
        @endif
        <img src="{{ asset('assets/img/logo-pln.png') }}" class="img-fluid w-50 mt-5" alt="Logo">
        <h1 class="text-white my-4">PLN UP3 Bintaro</h1>
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="input-group mb-3">
                <span class="input-group-text bg-transparent" id="basic-addon1"><i
                        class="fa fa-user text-white"></i></span>
                <input type="text" name="u" class="form-control bg-transparent" placeholder="Username"
                    required>
            </div>
            <div class="input-group mb-5">
                <span class="input-group-text bg-transparent" id="basic-addon1"><i
                        class="fa fa-lock text-white"></i></span>
                <input type="password" name="p" class="form-control bg-transparent" placeholder="Password"
                    required>
            </div>
            <div class="mb-3">
                <button class="btn btn-light w-100 text-primary">LOGIN</button>
            </div>
            <div class="text-end">
                <a href="#" class="text-white text-decoration-none">Forgot password?</a>
            </div>
        </form>
    </div>
    <script src="{{ asset('assets/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
