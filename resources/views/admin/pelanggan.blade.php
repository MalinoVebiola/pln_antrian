@extends('layouts.admin')

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

                            <th>TGL LAP</th>
                            <th>NO.KTP</th>
                            <th>NAMA</th>
                            <th>NO.NPWP</th>
                            <th>NO.HP</th>
                            <th>BIDANG KELUHAN</th>
                            <th>URAIAN KELUHAN</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporans as $index => $laporan)
                            <tr>

                                <td>{{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d-m-Y') }}</td>
                                <td>{{ $laporan->antrian->pengguna->no_ktp }}</td>
                                <td>{{ $laporan->antrian->pengguna->nama }}</td>
                                <td>{{ $laporan->antrian->pengguna->no_npwp }}</td>
                                <td>{{ $laporan->antrian->pengguna->no_hp }}</td>
                                <td>{{ $laporan->bidang_keluhan }}</td>
                                <td>{{ $laporan->detail_keluhan }}</td>
                                <td>
                                    <button type="button" class="btn btn-info btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $laporan->id }}">
                                    Detail
                                    </button>


                                    <button type="button" class="btn btn-info btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $laporan->id }}">
                                        Edit
                                    </button>


                                    <form action="{{ route('laporan.destroy', $laporan->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-table2excel/1.1.0/jquery.table2excel.min.js"></script>



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
                        <div class="mb-3">
                            <label for="bidang_keluhan" class="form-label">Bidang Keluhan</label>
                            <select id="bidang_keluhan" name="bidang_keluhan" class="form-control" required>
                                <option value="Pemasaran">Pemasaran</option>
                                <option value="Konstruksi">Konstruksi</option>
                                <option value="Niaga">Niaga</option>
                                <option value="Jaringan">Jaringan</option>
                                <option value="TE">TE</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="detail_keluhan" class="form-label">Detail Keluhan</label>
                            <textarea id="detail_keluhan" name="detail_keluhan" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="surat" class="form-label">Upload Surat</label>
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

    @foreach ($laporans as $laporan)
    <div class="modal fade" id="editModal{{ $laporan->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $laporan->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $laporan->id }}">Edit Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form untuk Edit Laporan -->
                    <form id="editLaporanForm{{ $laporan->id }}" method="POST" action="{{ route('laporan.update', $laporan->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="tanggal_laporan" class="form-label">Tanggal Laporan</label>
                            <input type="date" name="tanggal_laporan" id="tanggal_laporan{{ $laporan->id }}" class="form-control" value="{{ $laporan->tanggal_laporan }}">
                        </div>
                        <div class="mb-3">
                            <label for="no_ktp" class="form-label">Nomor KTP</label>
                            <input type="text" name="no_ktp" id="no_ktp{{ $laporan->id }}" class="form-control" value="{{ $laporan->antrian->pengguna->no_ktp }}">
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" name="nama" id="nama{{ $laporan->id }}" class="form-control" value="{{ $laporan->antrian->pengguna->nama }}">
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" name="alamat" id="alamat{{ $laporan->id }}" class="form-control" value="{{ $laporan->antrian->pengguna->alamat }}">
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">Nomor HP</label>
                            <input type="text" name="no_hp" id="no_hp{{ $laporan->id }}" class="form-control" value="{{ $laporan->antrian->pengguna->no_hp }}">
                        </div>
                        <div class="mb-3">
                            <label for="layanan_via" class="form-label">Layanan Via</label>
                            <input type="text" name="layanan_via" id="layanan_via{{ $laporan->id }}" class="form-control" value="{{ $laporan->layanan_via }}">
                        </div>
                        <div class="mb-3">
                            <label for="bidang_keluhan" class="form-label">Bidang Keluhan</label>
                            <input type="text" name="bidang_keluhan" id="bidang_keluhan{{ $laporan->id }}" class="form-control" value="{{ $laporan->bidang_keluhan }}">
                        </div>
                        <div class="mb-3">
                            <label for="detail_keluhan" class="form-label">Detail Keluhan</label>
                            <input type="text" name="detail_keluhan" id="detail_keluhan{{ $laporan->id }}" class="form-control" value="{{ $laporan->detail_keluhan }}">
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
@endforeach


    <!-- Modal untuk Detail Laporan -->
    <!-- Modal untuk Detail Laporan -->
@foreach ($laporans as $laporan)
<div class="modal fade" id="detailModal{{ $laporan->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $laporan->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel{{ $laporan->id }}">Data Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Tampilkan data sebagai teks -->
                <div class="mb-3">
                    <label for="tanggal_laporan" class="form-label fw-bold">Tanggal Laporan</label>
                    <p>{{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d-m-Y') }}</p>
                </div>
                <div class="mb-3">
                    <label for="no_ktp" class="form-label fw-bold">Nomor KTP</label>
                    <p>{{ $laporan->antrian->pengguna->no_ktp }}</p>
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label fw-bold">Nama</label>
                    <p>{{ $laporan->antrian->pengguna->nama }}</p>
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label fw-bold">Alamat</label>
                    <p>{{ $laporan->antrian->pengguna->alamat }}</p>
                </div>
                <div class="mb-3">
                    <label for="no_hp" class="form-label fw-bold">Nomor HP</label>
                    <p>{{ $laporan->antrian->pengguna->no_hp }}</p>
                </div>
                <div class="mb-3">
                    <label for="no_npwp" class="form-label fw-bold">Nomor NPWP</label>
                    <p>{{ $laporan->antrian->pengguna->no_npwp }}</p>
                </div>
                <div class="mb-3">
                    <label for="layanan_via" class="form-label fw-bold">Layanan Via</label>
                    <p>{{ $laporan->layanan_via }}</p>
                </div>
                <div class="mb-3">
                    <label for="bidang_keluhan" class="form-label fw-bold">Bidang Keluhan</label>
                    <p>{{ $laporan->bidang_keluhan }}</p>
                </div>
                <div class="mb-3">
                    <label for="detail_keluhan" class="form-label fw-bold">Detail Keluhan</label>
                    <p>{{ $laporan->detail_keluhan }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach



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
    $(document).on('submit', 'form[id^="editLaporanForm"]', function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let formData = form.serialize();

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            success: function (response) {
                // Update tabel dengan data baru
                let laporanId = response.id;
                $(`#row-${laporanId} .tanggal_laporan`).text(response.tanggal_laporan);
                $(`#row-${laporanId} .nama`).text(response.nama);
                $(`#row-${laporanId} .no_ktp`).text(response.no_ktp);

                // Tutup modal
                $(`#editModal${laporanId}`).modal('hide');

                // Tambahkan refresh otomatis setelah pembaruan berhasil
                setTimeout(function () {
                    location.reload(); // Refresh penuh halaman
                }, 1000); // Tunggu 1 detik sebelum reload
            },
            error: function (xhr) {
                alert('Gagal memperbarui data.');
            }
        });
    });
    </script>



@endsection
