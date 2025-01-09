@extends('layouts.admin2')

@section('content')
    <div class="col py-3" style="margin-left: 300px; margin-top: 60px;">
        <h1 class="text-greenish">Dashboard</h1>
        <p class="text-greenish mb-3">see the number of complaint data here!!</p>
        <select class="form-control w-25">
            <option>Last 30 days</option>
            <option>Last 30 days</option>
            <option>Last 30 days</option>
        </select>
        <canvas id="myChart" class="w-75 m-auto"></canvas>
    </div>
    </div>
    </div>

    <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

    <script>
        <?php
        // Fetch the number of reports for each "bk" type in the last 30 days

        $laporan1 = \App\Models\Laporan::where('bidang_keluhan', 'Pemasaran')->where('tanggal_laporan', '>', now()->subDays(30))->count();

        $laporan2 = \App\Models\Laporan::where('bidang_keluhan', 'Konstruksi')->where('tanggal_laporan', '>', now()->subDays(30))->count();

        $laporan3 = \App\Models\Laporan::where('bidang_keluhan', 'Niaga')->where('tanggal_laporan', '>', now()->subDays(30))->count();

        $laporan4 = \App\Models\Laporan::where('bidang_keluhan', 'Jaringan')->where('tanggal_laporan', '>', now()->subDays(30))->count();

        $laporan5 = \App\Models\Laporan::where('bidang_keluhan', 'TE')->where('tanggal_laporan', '>', now()->subDays(30))->count();
        ?>


        // Data for the chart
        var xValues = ["Pemasaran", "Konstruksi", "Niaga", "Jaringan", "TE"];
        var yValues = [{{ $laporan1 }}, {{ $laporan2 }}, {{ $laporan3 }}, {{ $laporan4 }},
            {{ $laporan5 }}
        ];
        var barColors = ['#20545c', '#20545c', '#20545c', '#20545c', '#20545c'];

        new Chart("myChart", {
            type: "bar",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: "Complaint Data"
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: false, // Dimulai dari 0
                        step: 1 // Selisih 5
                    }
                }]
            },
            scales: {
                yAxes: [{
                    ticks: {
                        display: false // Sembunyikan tick default
                    },
                    gridLines: {
                        display: true,
                        stepSize: 5,
                        color: '#ccc'
                    }
                }]
            },
            xAxes: [{
                barPercentage: 0.6
            }]
        });
    </script>

    <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
@endsection
