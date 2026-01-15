@extends('layouts.app')

@section('title', 'Dashboard Laporan Penjualan')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0 overflow-hidden">
                <div class="card-header-custom d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-bar-chart text-white" style="font-size: 1.8rem;"></i>
                    </div>
                    <div>
                        <h1 class="h2 mb-1 text-white">Dashboard Analisis Penjualan</h1>
                        <p class="mb-0 text-white opacity-75">
                            <i class="bi bi-graph-up me-1"></i>Insight lengkap penjualan produk berdasarkan kategori
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Kategori -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0">
                <div class="card-header-custom">
                    <h3 class="mb-0 text-white"><i class="bi bi-funnel me-2"></i>Filter Laporan</h3>
                </div>
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('report.dashboard') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <label for="category" class="form-label fw-bold">
                                    <i class="bi bi-tags me-1"></i>Filter Berdasarkan Kategori
                                </label>
                                <select class="form-select form-control-custom" id="category" name="category" 
                                        onchange="this.form.submit()">
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
                                <div class="d-flex gap-2">
                                    <a href="{{ route('report.dashboard') }}" class="btn btn-outline-secondary flex-fill">
                                        <i class="bi bi-arrow-clockwise me-2"></i>Reset Filter
                                    </a>
                                    @if(!$categoryData->isEmpty())
                                    <a href="{{ route('report.download-excel', ['category' => $selectedCategory]) }}" 
                                       class="btn btn-success flex-fill">
                                        <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
                                    </a>
                                    @endif
                                    <a href="{{ route('report.transactions.pdf') }}" 
                                       class="btn btn-danger flex-fill">
                                        <i class="bi bi-file-earmark-pdf me-2"></i>Export PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Ringkas -->
    @if(!$categoryData->isEmpty())
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card card-custom shadow-soft border-0 h-100">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center p-3">
                            <i class="bi bi-box-seam text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h1 class="fw-bold text-primary mb-2">{{ number_format($categoryData->sum('total_qty')) }}</h1>
                    <h6 class="text-muted mb-0">Total Produk Terjual</h6>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card card-custom shadow-soft border-0 h-100">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center p-3">
                            <i class="bi bi-receipt text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h1 class="fw-bold text-success mb-2">{{ number_format($categoryData->sum('transaction_count')) }}</h1>
                    <h6 class="text-muted mb-0">Total Transaksi</h6>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card card-custom shadow-soft border-0 h-100">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center p-3">
                            <i class="bi bi-tags text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h1 class="fw-bold text-info mb-2">{{ number_format($categoryData->count()) }}</h1>
                    <h6 class="text-muted mb-0">Kategori Aktif</h6>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card card-custom shadow-soft border-0 h-100">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center p-3">
                            <i class="bi bi-basket text-warning" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h1 class="fw-bold text-warning mb-2">{{ number_format($productDetails->count()) }}</h1>
                    <h6 class="text-muted mb-0">Produk Terjual</h6>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Grafik Kategori -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 text-white"><i class="bi bi-bar-chart-line me-2"></i>Analisis Penjualan per Kategori</h3>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light btn-sm" onclick="toggleChartType()">
                            <i class="bi bi-arrow-repeat me-1"></i>Ganti Tampilan
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($categoryData->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-graph-up" style="font-size: 3rem; color: var(--gray);"></i>
                            <h4 class="mt-3 text-muted">Belum ada data penjualan</h4>
                            <p class="text-muted">Tidak ada data penjualan untuk kategori yang dipilih</p>
                        </div>
                    @else
                        <div style="height: 400px;">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Detail Produk -->
    <div class="row">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 text-white"><i class="bi bi-table me-2"></i>Detail Penjualan Produk</h3>
                    <small class="text-white opacity-75">
                        Total {{ $productDetails->count() }} produk
                    </small>
                </div>
                <div class="card-body p-0">
                    @if($productDetails->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-box-seam" style="font-size: 3rem; color: var(--gray);"></i>
                            <h4 class="mt-3 text-muted">Belum ada data produk</h4>
                            <p class="text-muted">Tidak ada data detail produk untuk ditampilkan</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th width="30%">Produk</th>
                                        <th width="20%">Kategori</th>
                                        <th width="15%" class="text-center">Qty Terjual</th>
                                        <th width="15%" class="text-end">Harga Rata-rata</th>
                                        <th width="20%" class="text-end">Total Nilai Penjualan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $grandTotal = 0; @endphp
                                    @foreach($productDetails as $detail)
                                    @php $totalValue = $detail->total_qty * $detail->avg_price; $grandTotal += $totalValue; @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-box text-primary"></i>
                                                </div>
                                                <div>
                                                    <strong class="d-block">{{ $detail->product_name }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                                <i class="bi bi-tag me-1"></i>{{ $detail->category_name }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info rounded-pill px-3 py-2">
                                                <i class="bi bi-box-seam me-1"></i>{{ $detail->total_qty }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold">
                                            Rp {{ number_format($detail->avg_price, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end">
                                            <strong class="text-primary">Rp {{ number_format($totalValue, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @if($productDetails->count() > 5)
                                <tfoot>
                                    <tr class="table-light">
                                        <td colspan="4" class="text-end fw-bold">Total Keseluruhan:</td>
                                        <td class="text-end">
                                            <h5 class="fw-bold text-primary mb-0">
                                                Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                            </h5>
                                        </td>
                                    </tr>
                                </tfoot>
                                @endif
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
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
    let categoryChart;
    let chartType = 'bar'; // Default chart type
    
    @if(!$categoryData->isEmpty())
    // Prepare data for chart
    const categoryLabels = {!! json_encode($categoryData->pluck('category_name')) !!};
    const categoryQty = {!! json_encode($categoryData->pluck('total_qty')) !!};
    const transactionCounts = {!! json_encode($categoryData->pluck('transaction_count')) !!};

    // Generate colors based on primary theme
    function generateColors(count) {
        const colors = [];
        const baseHue = 240; // Blue hue
        for (let i = 0; i < count; i++) {
            const hue = (baseHue + (i * 30)) % 360;
            colors.push(`hsla(${hue}, 70%, 60%, 0.7)`);
        }
        return colors;
    }

    const backgroundColors = generateColors(categoryLabels.length);
    const borderColors = backgroundColors.map(color => color.replace('0.7', '1'));

    function initChart(type = 'bar') {
        const ctx = document.getElementById('categoryChart').getContext('2d');
        
        if (categoryChart) {
            categoryChart.destroy();
        }
        
        const datasetConfig = type === 'bar' ? {
            label: 'Jumlah Produk Terjual (Qty)',
            data: categoryQty,
            backgroundColor: backgroundColors,
            borderColor: borderColors,
            borderWidth: 2,
            borderRadius: 8,
            yAxisID: 'y'
        } : {
            label: 'Jumlah Produk Terjual (Qty)',
            data: categoryQty,
            backgroundColor: 'rgba(67, 97, 238, 0.1)',
            borderColor: 'rgba(67, 97, 238, 1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            yAxisID: 'y'
        };

        categoryChart = new Chart(ctx, {
            type: type,
            data: {
                labels: categoryLabels,
                datasets: [
                    datasetConfig,
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
                maintainAspectRatio: false,
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
                                size: 12,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: Math.max(1, Math.ceil(Math.max(...categoryQty) / 5))
                        },
                        grid: {
                            drawBorder: false
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
                                size: 12,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: Math.max(1, Math.ceil(Math.max(...transactionCounts) / 5))
                        },
                        grid: {
                            drawOnChartArea: false,
                            drawBorder: false
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Kategori Produk',
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    function toggleChartType() {
        chartType = chartType === 'bar' ? 'line' : 'bar';
        initChart(chartType);
    }

    // Initialize chart
    document.addEventListener('DOMContentLoaded', function() {
        initChart();
    });
    @endif

    // Table sorting functionality
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.querySelector('.table-custom');
        if (table) {
            const headers = table.querySelectorAll('thead th');
            headers.forEach((header, index) => {
                header.style.cursor = 'pointer';
                header.addEventListener('click', () => {
                    sortTable(index);
                });
            });
        }
    });

    function sortTable(columnIndex) {
        const table = document.querySelector('.table-custom');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        const isAscending = !table.dataset.sortAsc || table.dataset.sortAsc === 'false';
        
        rows.sort((a, b) => {
            const aText = a.children[columnIndex].textContent.trim();
            const bText = b.children[columnIndex].textContent.trim();
            
            // Check if content is numeric
            const aNum = parseFloat(aText.replace(/[^0-9.-]+/g, ""));
            const bNum = parseFloat(bText.replace(/[^0-9.-]+/g, ""));
            
            if (!isNaN(aNum) && !isNaN(bNum)) {
                return isAscending ? aNum - bNum : bNum - aNum;
            }
            
            return isAscending ? aText.localeCompare(bText) : bText.localeCompare(aText);
        });
        
        // Clear existing rows
        rows.forEach(row => tbody.removeChild(row));
        
        // Add sorted rows
        rows.forEach(row => tbody.appendChild(row));
        
        table.dataset.sortAsc = isAscending;
    }
</script>

<style>
    .table-custom tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.03) !important;
    }
    
    .table-custom tbody tr td {
        vertical-align: middle;
        padding: 1rem;
    }
    
    .table-custom thead th {
        background-color: #f8f9fa !important;
        color: var(--dark) !important;
        border-bottom: 2px solid var(--primary);
        font-weight: 600;
        padding: 1rem;
    }
    
    .table-custom thead th:hover {
        background-color: rgba(67, 97, 238, 0.1) !important;
    }
    
    .badge.rounded-pill {
        padding: 0.5rem 1rem;
    }
    
    .card-custom {
        transition: transform 0.3s ease;
    }
    
    .card-custom:hover {
        transform: translateY(-3px);
    }
</style>
@endsection