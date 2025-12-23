@extends('templates.sidebar')

@section('content')
    <div class="container">
        <h5 class="my-3">Grafik User</h5>
        @if (Session::get('success'))
            <div class="alert alert-success">{{Session::get('success')}} <b>Selamat Datang, {{Auth::user()->name}}</b></div>
        @endif
        <div class="row mt-5">
            <div class="col-12">
                <canvas id="chartBar"></canvas>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        let labels = null;
        let data = null;
        let dataPie = null;

        $(function () {
            $.ajax({
                url: "{{ route('admin.users.chart') }}",
                method: "GET",
                success: function (response) {
                    labels = response.labels;
                    data = response.data;
                    chartBar();
                },
                error: function (err) {
                    alert('Gagal mengambil data user untuk grafik!')
                }
            })
        });

        const ctx = document.getElementById('chartBar');
        function chartBar() {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Registrasi User per Hari (30 hari terakhir)',
                        data: data,
                        borderWidth: 1,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true
                        }
                    }
                }
            });
        };
    </script>
@endpush