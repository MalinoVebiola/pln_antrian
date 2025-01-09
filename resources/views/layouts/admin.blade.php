<?php ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/fontawesome/all.min.js"></script>
    <script src="../assets/jquery.autocomplete.min.js"></script>
    <link href="../assets/datatables/datatables.min.css" rel="stylesheet">
    <link href="../assets/jquery-3.2.1.min.js" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<div class="top-line"></div>
@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ session('error') }}",
            confirmButtonText: 'OK'
        });
    </script>
@endif


<body>
    <div class=" container-fluid">
        <div class="row flex-nowrap">
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-greenish position-fixed">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <a href="/"
                        class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <img src="../assets/img/logo-pln.png" alt="errorIMG" class="w-25"
                            style="width: 200px; height: 120px;">
                        <div class="ms-3">
                            <h3 class="text-white" style="font-size: 40px;">PLN UP3 Bintaro</h3>
                        </div>
                    </a>
                    <div class="container bg-greenishflat text-center p-4 mt-5">
                        <a href="{{ route('admin.index') }}"
                            class="text-decoration-none text-white sidebar-link sidebar-item">
                            <h5><i class="fa fa-house me-3"></i>Dashboard</h5>
                        </a>
                    </div>
                    <div class="container bg-greenishflat text-center p-4 mt-3">
                        <a href="{{ route('laporan') }}"
                            class="text-decoration-none text-white sidebar-link sidebar-item">
                            <h5><i class="fa fa-user me-3"></i>Data Pelanggan</h5>
                        </a>
                    </div>
                    <div class="container bg-greenishflat text-center p-4 mt-3">
                        <a href="{{ route('admin.antrian') }}"
                            class="text-decoration-none text-white sidebar-link sidebar-item">
                            <h5><i class="fa fa-copy me-3"></i>Data Antrian</h5>
                        </a>
                    </div>
                    <div class="container bg-greenishflat text-center p-4 mt-3">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link text-white text-decoration-none ms-3"
                                    style="line-height: 50px; margin-left: 20px;">
                                Logout
                            </button>
                        </form>
                    </div>
                    <hr>
                    <div class="dropdown pb-4 mx-auto mt-auto">
                        <a href="#"
                            class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                            id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="../assets/img/user.png" alt="hugenerd" width="30" height="30"
                                class="rounded-circle bg-white p-1">
                            <span
                                class="d-none d-sm-inline mx-4"><?= isset($_SESSION['akun']['username']) ? $_SESSION['akun']['username'] : 'Admin' ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link text-white text-decoration-none ms-3"
                                        style="line-height: 50px; margin-left: 20px;">
                                    Logout
                                </button>
                            </form>
                        </ul>
                    </div>
                </div>
            </div>



            @yield('content')


            @yield('scripts')



            <script src="{{ asset('assets/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
