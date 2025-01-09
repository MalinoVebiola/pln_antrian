<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue System</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <script src="{{ asset('assets/fontawesome/all.min.js') }}"></script>
</head>

<body class="bg-greenish">
    <div class="bg-greenish w-100 d-flex justify-content-between p-3">
        <h1 class="text-white">ANTRIAN USER</h1>
        <div>
            @if (session('login'))
                <a href="?out=1" style="line-height: 50px; margin-left: 20px"
                    class="text-white text-decoration-none">{{ akun('nama', session('login')) }}</a>
                <a href="{{ route('antrian.laporan') }}" style="line-height: 50px; margin-left: 20px"
                    class="text-white text-decoration-none">Laporan</a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link text-white text-decoration-none ms-3"
                                style="line-height: 50px; margin-left: 20px;">
                            Logout
                        </button>
                    </form>
            @else
                <a href="{{ route('login') }}" style="line-height: 50px; margin-left: 20px;"
                    class="text-white text-decoration-none">Login</a>
                <a href="#" style="line-height: 50px;margin-left: 20px;"
                    class="text-white text-decoration-none ms-3">Bantuan</a>
            @endif
        </div>
    </div>
    @yield('content')


    @yield('scripts')



    <script src="{{ asset('assets/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
