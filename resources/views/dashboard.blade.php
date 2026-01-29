@extends("template.master")

@section("content")
    <div class="card">
        <div class="card-header">
            <h5>Rekap Peminjaman Mingguan</h5>
        </div>
        <div class="card-body" style="height: 400px;"> {{-- Give enough height --}}
            <canvas id="peminjamanChart"></canvas>
        </div>
    </div>
@endsection

@push("js")
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('peminjamanChart').getContext('2d');
        const peminjamanChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($days), // ['2026-01-27', '2026-01-28', ...]
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: @json($totals), // [5, 2, 0, ...]
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // let it fill container
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });
    </script>
@endpush
