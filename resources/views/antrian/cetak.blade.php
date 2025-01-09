<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nomor Antrian</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
</head>

<body onload="window.print();">
    <div class="container text-center mt-5">
        <h1>Nomor Antrian Anda</h1>
        <h2>{{ $antrian->nomor_antrian }}</h2>
        <p>Tanggal: {{ \Carbon\Carbon::parse($antrian->tanggal)->format('d-m-Y') }}</p>
    </div>
</body>

</html>
