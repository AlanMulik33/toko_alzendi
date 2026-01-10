<div>
    @extends('layouts.app')

@section('content')
<h3>Grafik Transaksi</h3>

<canvas id="chart"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('chart'), {
    type: 'bar',
    data: {
        labels: @json($labels),
        datasets: [{
            label: 'Total Transaksi',
            data: @json($totals)
        }]
    }
});
</script>
@endsection

</div>
