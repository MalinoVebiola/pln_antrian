@extends('layouts.pengguna')

@section('content')
    <style>
        body {

            .table th {
                background-color: #f0f0f0;
            }
            .header-row {
                background-color: #e9e9e9;
            }

        }
    </style>



    <div class="col py-3" style="margin-left: 300px; margin-top: 60px;">
        <br>
        <br>
        <div id="btn-place"></div>

        <div class="container py-3">
            <!-- Form Search -->
            {{-- <div class="row mb-3">
            <label for="search-input" class="col-sm-1 col-form-label">Search</label>
            <div class="col-sm-6 d-flex justify-content-end">
                <input type="text" id="search-input" class="form-control" placeholder="Cari Laporan...">
            </div>


        </div> --}}

            <!-- Tabel Laporan -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>TGL LAP</th>
                            <th>NO.KTP</th>
                            <th>NAMA</th>
                            <th>NO.NPWP</th>
                            <th>NO.HP</th>
                            <th>BIDANG KELUHAN</th>
                            <th>URAIAN KELUHAN</th>
                            <th>STATUS</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporans as $index => $laporan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d-m-Y') }}</td>
                                <td>{{ $laporan->antrian->pengguna->no_ktp }}</td>
                                <td>{{ $laporan->antrian->pengguna->nama }}</td>
                                <td>{{ $laporan->antrian->pengguna->no_npwp }}</td>
                                <td>{{ $laporan->antrian->pengguna->no_hp }}</td>
                                <td>{{ $laporan->bidang_keluhan }}</td>
                                <td>{{ $laporan->detail_keluhan }}</td>
                                <td>{{ $laporan->status }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>




<script>
    function updateStatus(id) {
    fetch(`/laporan/update-status/${id}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status berhasil diperbarui menjadi: ' + data.status);
                location.reload();
            }
        });
}
</script>

    <script>
        // Fungsi untuk filter berdasarkan pencarian
        document.getElementById('search-input').addEventListener('input', function(e) {
            var searchValue = e.target.value.toLowerCase();
            var rows = document.querySelectorAll('#laporanTable tbody tr');

            rows.forEach(function(row) {
                var cells = row.querySelectorAll('td');
                var isMatch = false;
                cells.forEach(function(cell) {
                    if (cell.textContent.toLowerCase().includes(searchValue)) {
                        isMatch = true;
                    }
                });

                if (isMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

    <script>
        function fetchDataByKTP(noKtp) {
            if (noKtp) {
                fetch(`/laporan/get-data/${noKtp}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('nama').value = data.data.nama;
                            document.getElementById('alamat').value = data.data.alamat;
                            document.getElementById('no_hp').value = data.data.no_hp;
                            document.getElementById('no_npwp').value = data.data.no_npwp;
                            document.getElementById('tarif_daya').value = data.data.tarif_daya;
                        } else {
                            alert('Data tidak ditemukan');
                            clearFields();
                        }
                    })
                    .catch(error => console.error('Error fetching data:', error));
            } else {
                clearFields();
            }
        }

        function clearFields() {
            document.getElementById('nama').value = '';
            document.getElementById('alamat').value = '';
            document.getElementById('no_hp').value = '';
            document.getElementById('no_npwp').value = '';
            document.getElementById('tarif_daya').value = '';
        }
    </script>

    <!-- CSS DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

    <!-- JS DataTables -->
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js">
    </script>

    <!-- JS untuk tombol ekspor -->
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js">
    </script>


    <script>
        var table = $('#dataTable').DataTable({
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
        $('#btn-place').html(table.buttons().container());
    </script>

    <script>
        function toggleVisibility(id) {
    fetch(`/laporan/toggle-visibility/${id}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Data berhasil ' + (data.is_hidden ? 'disembunyikan' : 'ditampilkan kembali'));
                location.reload();
            }
        });
}

    </script>


@endsection
