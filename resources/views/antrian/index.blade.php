<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judul }}</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <script src="{{ asset('assets/fontawesome/all.min.js') }}"></script>
</head>

<body class="bg-greenish">
    <div class="bg-greenish w-100 d-flex justify-content-between p-3">
        <h1 class="text-white">{{ $judul }}</h1>
        <div>
            @if (Auth::check())
                <a href="{{ route('antrian.laporan') }}" class="text-white text-decoration-none"
                    style="line-height: 50px; margin-left: 20px;">Laporan</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none ms-3"
                        style="line-height: 50px; margin-left: 20px;">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-white text-decoration-none"
                    style="line-height: 50px; margin-left: 20px;">Login</a>
                <a href="#" class="text-white text-decoration-none ms-3"
                    style="line-height: 50px;margin-left: 20px;">Bantuan</a>
            @endif
        </div>
    </div>

    <div class="container w-50 text-center m-auto">
        <div class="m-auto w-50 mt-5">
            <img src="{{ asset('assets/img/logo-pln.png') }}" alt="errorIMG" class="img-fluid w-25">
            <h2 class="d-inline text-white">{{ $namainstansi }}</h2>
        </div>
        <br>
        <div class="m-auto w-50 mt-3 border border-1 border-white p-4">
            <h1 class="text-white">Nomor antrian saat ini</h1>
            <h1 class="text-white" id="antrian-sekarang">{{ $antrianSekarang }}</h1>
        </div>

        <div class="m-auto w-50 mt-3 mb-5">
            <div>
                @if (Auth::check())
                    @if ($nomorAntrian)
                        <!-- Tampilkan jika nomor antrian ada -->
                        <p class="text-white">Nomor Antrian Anda: <strong>{{ $nomorAntrian->nomor_antrian }}</strong>
                        </p>

                        <!-- Tombol Cetak -->
                        <form id="ulangAmbilForm" action="{{ route('antrian.ambil') }}" method="POST"
                            style="display:inline;">
                            @csrf
                            <button type="button" class="btn btn-primary" onclick="submitForm()">Ulang Ambil Nomor
                                Antrian</button>
                        </form>
                        <a href="{{ route('antrian.cetak', ['id' => $nomorAntrian->id_antrian]) }}" target="_blank"
                            class="btn btn-warning">Cetak</a>
                    @else
                        <!-- Tampilkan jika nomor antrian belum ada -->
                        <form id="ulangAmbilForm" action="{{ route('antrian.ambil') }}" method="POST"
                            style="display:inline;">
                            @csrf
                            <button type="button" class="btn btn-primary" onclick="submitForm()">Ambil Nomor
                                Antrian</button>
                        </form>
                    @endif
                @else
                    <!-- Tampilkan tombol Register jika belum login -->
                    <p class="fw-bold" style="color: white">Selamat datang, klik register jika belum memiliki akun</p>
                    <a href="{{ route('pengguna.register') }}" class="btn btn-primary">Registrasi</a>
                @endif
            </div>
        </div>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            function submitForm() {
                var form = document.getElementById('ulangAmbilForm');
                var formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest' // Menandakan permintaan AJAX
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json(); // Mengubah respons menjadi JSON jika response OK
                    })
                    .then(data => {
                        if (data.message && data.nomor_antrian) {
                            alert(data.message + '\nNomor Antrian: ' + data.nomor_antrian);
                            location.reload(); // Refresh halaman setelah sukses
                        } else {
                            alert('Terjadi kesalahan. Coba lagi.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan jaringan.');
                    });
            }
        </script>








</body>

</html>
