@extends('layouts.admin2')

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
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ubahModal">
            Tambah Data
        </button>
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
                            <th>Aksi</th>
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
                                <td>

                                    <a data-bs-target="#detailModal"data-bs-toggle="modal"
                                    class="btn btn-info btn-sm">Detail</a>
                                        <button class="btn btn-primary btn-sm" onclick="updateStatus('{{ $laporan->id }}')">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                        <button class="btn btn-secondary btn-sm" onclick="toggleVisibility('{{ $laporan->id }}')" id="toggle-btn-{{ $laporan->id }}">
                                            <i class="fas fa-eye-slash" id="icon-{{ $laporan->id }}"></i>
                                        </button>


                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>


 <!-- Modal untuk Detail Laporan -->
 @foreach ($laporans as $laporan)
 <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="editModalLabel">Data</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <!-- Tampilkan data sebagai teks -->
                 <div class="mb-3">
                     <label for="tanggal_laporan" class="form-label fw-bold">Tanggal Laporan</label>
                     <a
                         id="tanggal_laporan">{{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d-m-Y') }}</a>
                 </div>
                 <div class="mb-3">
                     <label for="no_ktp" class="form-label fw-bold">Nomor KTP</label>
                     <a id="no_ktp">{{ $laporan->antrian->pengguna->no_ktp }}</a>
                 </div>
                 <div class="mb-3">
                     <label for="nama" class="form-label fw-bold">Nama</label>
                     <a id="nama">{{ $laporan->antrian->pengguna->nama }}</a>
                 </div>
                 <div class="mb-3">
                     <label for="alamat" class="form-label fw-bold">Alamat</label>
                     <a id="alamat">{{ $laporan->antrian->pengguna->alamat }}</a>
                 </div>
                 <div class="mb-3">
                     <label for="no_hp" class="form-label fw-bold">Nomor HP</label>
                     <a id="no_hp">{{ $laporan->antrian->pengguna->no_hp }}</a>
                 </div>
                 <div class="mb-3">
                     <label for="no_npwp" class="form-label fw-bold">Nomor NPWP</label>
                     <a id="no_npwp">{{ $laporan->antrian->pengguna->no_npwp }}</a>
                 </div>
                 <div class="mb-3">
                     <label for="layanan_via" class="form-label fw-bold">Layanan Via</label>
                     <a id="layanan_via">{{ $laporan->layanan_via }}</a>
                 </div>
                 <div class="mb-3">
                     <label for="bidang_keluhan" class="form-label fw-bold">Bidang Keluhan</label>
                     <a id="bidang_keluhan">{{ $laporan->bidang_keluhan }}</a>
                 </div>
                 <div class="mb-3">
                     <label for="detail_keluhan" class="form-label fw-bold">Detail Keluhan</label>
                     <a id="detail_keluhan">{{ $laporan->detail_keluhan }}</a>
                 </div>

                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                 </div>
             </div>
         </div>
     </div>
 </div>
@endforeach


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
        fetch(`/laporan/toggle-visibility/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const iconElement = document.getElementById('icon-' + id);
                const buttonElement = document.getElementById('toggle-btn-' + id);

                // Update icon based on visibility status
                if (data.is_hidden) {
                    iconElement.classList.remove('fa-eye');
                    iconElement.classList.add('fa-eye-slash');
                } else {
                    iconElement.classList.remove('fa-eye-slash');
                    iconElement.classList.add('fa-eye');
                }

                alert('Data berhasil ' + (data.is_hidden ? 'disembunyikan' : 'ditampilkan kembali'));
            }
        });
    }
    </script>





    <!-- Modal for Add Laporan -->
    <div class="modal fade" id="ubahModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="ubahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ubahModalLabel">Tambah Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('laporan.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="tanggal_laporan" class="form-label">Tanggal Laporan</label>
                            <input type="date" name="tanggal_laporan" id="tanggal_laporan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_ktp" class="form-label">Nomor KTP</label>
                            <input type="text" name="no_ktp" id="no_ktp" class="form-control"
                                oninput="fetchDataByKTP(this.value)" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" id="nama" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea id="alamat" class="form-control" readonly></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">Nomor HP</label>
                            <input type="text" id="no_hp" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="no_npwp" class="form-label">Nomor NPWP</label>
                            <input type="text" id="no_npwp" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="tarif_daya" class="form-label">Tarif/Daya</label>
                            <input type="text" id="tarif_daya" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="layanan_via" class="form-label">Layanan Via</label>
                            <input type="text" name="layanan_via" id="layanan_via" class="form-control" required>
                        </div>

                        </div>
                        <div class="mb-3">
                            <label for="detail_keluhan" class="form-label">Detail Keluhan</label>
                            <textarea id="detail_keluhan" name="detail_keluhan" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="<div class="mb-3">
                            <label for="bidang_keluhan" class="form-label">Bidang Keluhan</label>
                            <select id="bidang_keluhan" name="bidang_keluhan" class="form-control" required>
                                <option value="Pemasaran">Pemasaran</option>
                                <option value="Konstruksi">Konstruksi</option>
                                <option value="Niaga">Niaga</option>
                                <option value="Jaringan">Jaringan</option>
                                <option value="TE">TE</option>
                            </select>surat" class="form-label">Upload Surat</label>
                            <input type="file" name="surat" id="surat" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
