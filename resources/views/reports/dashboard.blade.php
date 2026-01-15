@extends('layouts.app')

@section('title', 'Dashboard Laporan Penjualan')

@section('content')
<div class="container-fluid mt-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>📊 Dashboard Penjualan Produk</h1>
            <p class="text-muted">Analisis penjualan produk berdasarkan kategori</p>
        </div>
    </div>

    <!-- Filter Kategori -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('report.dashboard') }}" class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label for="category" class="form-label">Filter Kategori</label>
                            <select class="form-select" id="category" name="category" onchange="this.form.submit()">
                                <option value="all" @selected(!$selectedCategory || $selectedCategory === 'all')>
                                    📊 Semua Kategori
                                </option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected($selectedCategory == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('report.dashboard') }}" class="btn btn-secondary">Reset Filter</a>
                            <a href="{{ route('report.download-excel', ['category' => $selectedCategory]) }}" class="btn btn-success">
                                📥 Download Excel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Kategori -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📈 Penjualan Produk per Kategori</h5>
                </div>
                <div class="card-body">
                    @if($categoryData->isEmpty())
                        <div class="alert alert-info">
                            Tidak ada data penjualan untuk kategori yang dipilih
                        </div>
                    @else
                        <canvas id="categoryChart" height="80"></canvas>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Ringkas -->
    @if(!$categoryData->isEmpty())
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center border-primary">
                <div class="card-body">
                    <h3 class="text-primary">{{ $categoryData->sum('total_qty') }}</h3>
                    <p class="text-muted mb-0">Total Produk Terjual</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h3 class="text-success">{{ $categoryData->sum('transaction_count') }}</h3>
                    <p class="text-muted mb-0">Total Transaksi</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-info">
                <div class="card-body">
                    <h3 class="text-info">{{ $categoryData->count() }}</h3>
                    <p class="text-muted mb-0">Kategori Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h3 class="text-warning">{{ $productDetails->count() }}</h3>
                    <p class="text-muted mb-0">Produk Terjual</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabel Detail Produk -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">📦 Detail Penjualan Produk</h5>
                </div>
                <div class="card-body">
                    @if($productDetails->isEmpty())
                        <div class="alert alert-info">
                            Tidak ada data detail produk
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>📦 Produk</th>
                                        <th>🏷️ Kategori</th>
                                        <th class="text-end">📊 Qty Terjual</th>
                                        <th class="text-end">💰 Harga Rata-rata</th>
                                        <th class="text-end">💵 Total Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($productDetails as $detail)
                                        <tr>
                                            <td>
                                                <strong>{{ $detail->product_name }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $detail->category_name }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-info">{{ $detail->total_qty }}</span>
                                            </td>
                                            <td class="text-end">
                                                Rp {{ number_format($detail->avg_price, 0, ',', '.') }}
                                            </td>
                                            <td class="text-end">
                                                <strong>Rp {{ number_format($detail->total_qty * $detail->avg_price, 0, ',', '.') }}</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    @if(!$categoryData->isEmpty())
    // Prepare data for chart
    const categoryLabels = {!! json_encode($categoryData->pluck('category_name')) !!};
    const categoryQty = {!! json_encode($categoryData->pluck('total_qty')) !!};
    const transactionCounts = {!! json_encode($categoryData->pluck('transaction_count')) !!};

    // Generate random colors
    function generateColors(count) {
        const colors = [];
        for (let i = 0; i < count; i++) {
            const hue = (i * 360 / count) % 360;
            colors.push(`hsl(${hue}, 70%, 60%)`);
        }
        return colors;
    }

    const backgroundColors = generateColors(categoryLabels.length);
    const borderColors = backgroundColors.map(color => color.replace('60%', '40%'));

    // Category Chart
    const ctx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: categoryLabels,
            datasets: [
                {
                    label: 'Jumlah Produk Terjual (Qty)',
                    data: categoryQty,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 2,
                    borderRadius: 5,
                    yAxisID: 'y'
                },
                {
                    label: 'Jumlah Transaksi',
                    data: transactionCounts,
                    type: 'line',
                    borderColor: '#FF6384',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    borderWidth: 3,
                    pointRadius: 6,
                    pointBackgroundColor: '#FF6384',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    tension: 0.4,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        padding: 20,
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 13,
                        weight: 'bold'
                    },
                    callbacks: {
                        label: function(context) {
                            if (context.datasetIndex === 0) {
                                return 'Qty Terjual: ' + context.parsed.y + ' unit';
                            } else {
                                return 'Transaksi: ' + context.parsed.y;
                            }
                        }
                    }
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Jumlah Produk (Qty)',
                        font: {
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Jumlah Transaksi',
                        font: {
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                },
                x: {
                    stacked: false,
                    title: {
                        display: true,
                        text: 'Kategori Produk',
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            }
        }
    });
    @endif
</script>

@endsection