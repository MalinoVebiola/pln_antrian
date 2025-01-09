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
        <h2 class="mb-4">Antrian Pelanggan</h2>
        <br>
        <div>
            <p>Antrian Sekarang: <span id="antrian-sekarang">-</span></p>
            <p>Antrian Selanjutnya: <span id="antrian-selanjutnya">-</span></p>
        </div>
        <br>

        <table class="table table-bordered" id="Tabledata">
            <thead>
                <tr>
                    <th>Nomor Antrian</th>
                    <th>Tanggal</th>
                    <th>Nama Pengguna</th>
                    <th>No KTP</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Menampilkan data antrian -->
                @foreach ($antrian as $item)
                <tr>
                    <td>{{ $item['nomor_antrian'] }}</td>
                    <td>{{ $item['tanggal'] }}</td>
                    <td>{{ $item->pengguna->nama }}</td>
                    <td>{{ $item->pengguna->no_ktp }}</td>
                    <td>{{ $item['status'] == 'Dipanggil' ? 'Dipanggil' : 'Menunggu' }}</td>
                    <td>
                        @if ($item['status'] == 'Menunggu')
                            <button class="btn btn-primary btn-sm" data-id="{{ $item['id_antrian'] }}" onclick="panggilAntrian({{ $item['id_antrian'] }})">Panggil</button>
                        @elseif ($item['status'] == 'Dipanggil')
                            <button class="btn btn-secondary btn-sm" data-id="{{ $item['id_antrian'] }}" onclick="panggilAntrian({{ $item['id_antrian'] }})">Panggil Lagi</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/antrian/create" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>No Antrian</label>
                            <input type="number" name="no_antrian" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.responsivevoice.org/responsivevoice.js?key=jQZ2zcdq"></script>
    <audio id="tingtung" src="../assets/audio/tingtung.mp3"></audio>

    <!-- CSS DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

    <!-- JS DataTables -->
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#Tabledata').DataTable({
                processing: false,
                serverSide: true,
                ajax: {
                    url: '/antrian/getAntrianData',
                    method: 'GET',
                    dataSrc: function(json) {
                        return json.data;
                    }
                },
                columns: [
                    { data: 'nomor_antrian' },
                    { data: 'tanggal' },
                    { data: 'pengguna.nama' },
                    { data: 'pengguna.no_ktp' },
                    { data: 'status' },
                    {
                        data: 'id_antrian',
                        render: function(data, type, row) {
                            if (row.status === "Menunggu") {
                                return `<button class="btn btn-success btn-sm" data-id="${data}" onclick="panggilAntrian(${data})">Panggil</button>`;
                            } else if (row.status === "Dipanggil") {
                                return `<button class="btn btn-secondary btn-sm" data-id="${data}" onclick="panggilAntrian(${data})">Panggil Lagi</button>`;
                            }
                            return '';
                        }
                    }
                ],
                order: [[0, "asc"]],
                iDisplayLength: 10,
            });

            window.panggilAntrian = function(id_antrian) {
                var bell = document.getElementById('tingtung');
                bell.pause();
                bell.currentTime = 0;
                bell.play();

                var durasiBell = bell.duration * 1000; // Durasi dalam milidetik
                var row = table.row($(`button[data-id=${id_antrian}]`).closest('tr'));
                var data = row.data();

                setTimeout(function() {
                    responsiveVoice.speak(
                        `Nomor Antrian, ${data.nomor_antrian}, atas nama ${data['pengguna.nama']} menuju loket A`,
                        "Indonesian Male",
                        { rate: 0.9, pitch: 1, volume: 1 }
                    );
                }, durasiBell);

                $.ajax({
                    type: "POST",
                    url: `/antrian/update/${id_antrian}`,
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: 'Dipanggil'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#antrian-sekarang').text(response.antrian_sekarang);
                            $('#antrian-selanjutnya').text(response.antrian_selanjutnya);
                            table.ajax.reload(null, false);
                        } else {
                            alert('Gagal memperbarui status antrian.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", status, error);
                        alert('Terjadi kesalahan saat memperbarui status antrian.');
                    }
                });
            };

            setInterval(function() {
                $.ajax({
                    url: '/antrian/getAntrianSummary',
                    method: 'GET',
                    success: function(data) {
                        $('#antrian-sekarang').text(data.antrian_sekarang || '-');
                        $('#antrian-selanjutnya').text(data.antrian_selanjutnya || '-');
                    },
                    error: function() {
                        console.error('Gagal memperbarui data antrian.');
                    }
                });
            }, 1000);
        });
    </script>


@endsection
