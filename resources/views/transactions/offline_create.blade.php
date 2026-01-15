@extends('layouts.app')

@section('title', 'Transaksi Offline (Kasir)')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0 overflow-hidden">
                <div class="card-header-custom d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-cash-coin text-white" style="font-size: 1.8rem;"></i>
                    </div>
                    <div>
                        <h1 class="h2 mb-1 text-white">Transaksi Offline (Kasir)</h1>
                        <p class="mb-0 text-white opacity-75">
                            <i class="bi bi-shop me-1"></i>Input transaksi customer offline
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-danger alert-custom">
                <div class="d-flex">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        <strong class="d-block">Validasi Error</strong>
                        <ul class="mb-0 mt-1 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card card-custom shadow-soft border-0">
                <div class="card-header-custom">
                    <h3 class="mb-0 text-white"><i class="bi bi-person-badge me-2"></i>Informasi Customer</h3>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.transactions.offline.store') }}" method="POST" id="offlineTransactionForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="customer_name" class="form-label fw-bold">
                                <i class="bi bi-person me-1"></i>Nama Customer
                            </label>
                            <input type="text" class="form-control form-control-custom" 
                                   id="customer_name" name="customer_name" 
                                   placeholder="Masukkan nama customer" required>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Product Items -->
            <div class="card card-custom shadow-soft border-0 mt-4">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 text-white"><i class="bi bi-cart me-2"></i>Detail Produk</h3>
                    <button type="button" class="btn btn-light" onclick="addItem()">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Produk
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom mb-0" id="itemsTable">
                            <thead>
                                <tr>
                                    <th width="40%">Produk</th>
                                    <th width="15%" class="text-center">Harga Satuan</th>
                                    <th width="15%" class="text-center">Kuantitas</th>
                                    <th width="15%" class="text-center">Subtotal</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <!-- Items will be added here dynamically -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Empty State -->
                    <div id="emptyItemsState" class="text-center py-5">
                        <i class="bi bi-cart" style="font-size: 3rem; color: var(--gray);"></i>
                        <h4 class="mt-3 text-muted">Belum ada produk</h4>
                        <p class="text-muted">Tambahkan produk untuk memulai transaksi</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card card-custom shadow-soft border-0 h-100">
                <div class="card-header-custom">
                    <h3 class="mb-0 text-white"><i class="bi bi-calculator me-2"></i>Ringkasan Pembayaran</h3>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted">Total Transaksi</label>
                        <h2 class="fw-bold text-primary mb-0" id="totalDisplay">Rp 0</h2>
                        <input type="hidden" name="total" id="total" value="0">
                    </div>
                    
                    <div class="mb-4">
                        <label for="uang_diterima" class="form-label fw-bold">
                            <i class="bi bi-cash-stack me-1"></i>Uang Diterima
                        </label>
                        <input type="number" class="form-control form-control-custom" 
                               id="uang_diterima" name="uang_diterima" min="0" 
                               placeholder="0" oninput="calculateChange()" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-success">Kembalian</label>
                        <h3 class="fw-bold text-success mb-0" id="kembalianDisplay">Rp 0</h3>
                        <input type="hidden" name="kembalian" id="kembalian" value="0">
                    </div>
                    
                    <input type="hidden" name="items" id="itemsInput" value="[]">
                    
                    <button type="submit" form="offlineTransactionForm" class="btn btn-primary-custom w-100 py-3">
                        <i class="bi bi-check-circle me-2"></i>Simpan & Cetak Nota
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary w-100 mt-3">
                        <i class="bi bi-x-circle me-2"></i>Batalkan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

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
    
    .item-row {
        transition: all 0.2s ease;
    }
    
    #emptyItemsState {
        display: none;
    }
    
    #kembalianDisplay {
        transition: all 0.3s ease;
    }
    
    .negative-change {
        color: var(--danger) !important;
    }
</style>

<script>
let rowCount = 0;
const products = {!! json_encode($products->map(fn($p) => [
    'id' => (int)$p->id, 
    'name' => $p->name, 
    'price' => (float)$p->price,
    'stock' => (int)$p->stock
])->values()) !!};

function showEmptyState(show) {
    const emptyState = document.getElementById('emptyItemsState');
    if (show) {
        emptyState.style.display = 'block';
    } else {
        emptyState.style.display = 'none';
    }
}

function addItem() {
    rowCount++;
    const rowId = `item_${rowCount}`;
    
    const row = `
        <tr id="${rowId}" class="item-row">
            <td>
                <select class="form-select form-control-custom item-product" 
                        data-row="${rowCount}" onchange="updateRow(${rowCount})">
                    <option value="">-- Pilih Produk --</option>
                    ${products.map(p => `
                        <option value="${p.id}" data-price="${p.price}" data-stock="${p.stock}">
                            ${p.name} - Rp ${p.price.toLocaleString('id-ID')} (Stok: ${p.stock})
                        </option>
                    `).join('')}
                </select>
            </td>
            <td class="text-center">
                <input type="text" class="form-control form-control-custom text-center item-price" 
                       value="0" readonly>
            </td>
            <td class="text-center">
                <input type="number" class="form-control form-control-custom text-center item-qty" 
                       value="1" min="1" max="999" 
                       onchange="updateRow(${rowCount})" oninput="updateRow(${rowCount})">
            </td>
            <td class="text-center">
                <input type="text" class="form-control form-control-custom text-center item-subtotal" 
                       value="0" readonly>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm" 
                        onclick="removeRow(${rowCount})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    `;
    
    document.getElementById('itemsBody').insertAdjacentHTML('beforeend', row);
    showEmptyState(false);
    updateRow(rowCount);
}

function updateRow(rowNum) {
    const row = document.getElementById(`item_${rowNum}`);
    if (!row) return;
    
    const select = row.querySelector('.item-product');
    const priceInput = row.querySelector('.item-price');
    const qtyInput = row.querySelector('.item-qty');
    const subtotalInput = row.querySelector('.item-subtotal');
    
    const productId = parseInt(select.value);
    let qty = parseInt(qtyInput.value) || 1;
    
    if (!productId) {
        priceInput.value = '0';
        subtotalInput.value = '0';
    } else {
        const product = products.find(p => p.id === productId);
        if (product) {
            // Validate stock
            if (qty > product.stock) {
                qty = product.stock;
                qtyInput.value = product.stock;
                showNotification('warning', `Stok ${product.name} hanya tersedia ${product.stock} unit`);
            }
            
            const subtotal = product.price * qty;
            priceInput.value = product.price.toLocaleString('id-ID');
            subtotalInput.value = subtotal.toLocaleString('id-ID');
        }
    }
    
    updateTotal();
    calculateChange();
}

function removeRow(rowNum) {
    const row = document.getElementById(`item_${rowNum}`);
    if (row) {
        row.remove();
    }
    
    // Check if no items left
    const itemRows = document.querySelectorAll('#itemsBody tr');
    if (itemRows.length === 0) {
        showEmptyState(true);
    }
    
    updateTotal();
    calculateChange();
}

function updateTotal() {
    let totalAmount = 0;
    let itemCount = 0;
    const items = [];
    
    for (let i = 1; i <= rowCount; i++) {
        const row = document.getElementById(`item_${i}`);
        if (!row) continue;
        
        const select = row.querySelector('.item-product');
        const qtyInput = row.querySelector('.item-qty');
        const subtotalInput = row.querySelector('.item-subtotal');
        
        if (select.value && qtyInput) {
            const product = products.find(p => p.id === parseInt(select.value));
            if (product) {
                itemCount++;
                const qty = parseInt(qtyInput.value) || 1;
                const subtotal = product.price * qty;
                totalAmount += subtotal;
                
                items.push({
                    product_id: product.id,
                    qty: qty,
                    price: product.price
                });
            }
        }
    }
    
    // Update display
    document.getElementById('totalDisplay').textContent = `Rp ${totalAmount.toLocaleString('id-ID')}`;
    document.getElementById('total').value = totalAmount;
    document.getElementById('itemsInput').value = JSON.stringify(items);
}

function calculateChange() {
    const total = parseFloat(document.getElementById('total').value) || 0;
    const uangDiterima = parseFloat(document.getElementById('uang_diterima').value) || 0;
    const kembalian = uangDiterima - total;
    
    const kembalianDisplay = document.getElementById('kembalianDisplay');
    const kembalianInput = document.getElementById('kembalian');
    
    if (kembalian >= 0) {
        kembalianDisplay.textContent = `Rp ${kembalian.toLocaleString('id-ID')}`;
        kembalianDisplay.classList.remove('negative-change');
    } else {
        kembalianDisplay.textContent = `-Rp ${Math.abs(kembalian).toLocaleString('id-ID')}`;
        kembalianDisplay.classList.add('negative-change');
    }
    
    kembalianInput.value = kembalian;
}

function showNotification(type, message) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-custom alert-dismissible fade show`;
    alert.innerHTML = `
        <i class="bi bi-${type === 'warning' ? 'exclamation-triangle' : 'info-circle'}-fill me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alert, container.firstChild);
    
    setTimeout(() => {
        alert.remove();
    }, 5000);
}

// Form validation
document.getElementById('offlineTransactionForm').addEventListener('submit', function(e) {
    const itemsInput = document.getElementById('itemsInput').value;
    const items = JSON.parse(itemsInput);
    
    if (items.length === 0) {
        e.preventDefault();
        showNotification('danger', 'Tambahkan minimal 1 produk untuk membuat transaksi');
        return;
    }
    
    const customerName = document.getElementById('customer_name').value.trim();
    if (!customerName) {
        e.preventDefault();
        showNotification('danger', 'Masukkan nama customer terlebih dahulu');
        return;
    }
    
    const uangDiterima = parseFloat(document.getElementById('uang_diterima').value) || 0;
    const total = parseFloat(document.getElementById('total').value) || 0;
    
    if (uangDiterima < total) {
        e.preventDefault();
        showNotification('danger', 'Uang diterima kurang dari total transaksi');
        return;
    }
    
    // Check stock availability
    let stockError = '';
    items.forEach(item => {
        const product = products.find(p => p.id === item.product_id);
        if (product && item.qty > product.stock) {
            stockError += `\n• ${product.name}: Permintaan ${item.qty}, stok tersedia ${product.stock}`;
        }
    });
    
    if (stockError) {
        e.preventDefault();
        showNotification('danger', 'Stok tidak mencukupi:' + stockError);
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    showEmptyState(true);
    // Auto-add first item
    addItem();
});
</script>
@endsection