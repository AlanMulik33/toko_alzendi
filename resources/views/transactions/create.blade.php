@extends('layouts.app')

@section('title', 'Buat Transaksi Baru')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0 overflow-hidden">
                <div class="card-header-custom d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-cart-plus text-white" style="font-size: 1.8rem;"></i>
                    </div>
                    <div>
                        <h1 class="h2 mb-1 text-white">Buat Transaksi Baru</h1>
                        <p class="mb-0 text-white opacity-75">
                            <i class="bi bi-receipt me-1"></i>Tambahkan produk untuk memulai transaksi
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

    <form action="{{ route('transactions.store') }}" method="POST" id="transactionForm">
        @csrf
        
        <!-- Customer & Address Info -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-custom shadow-soft border-0">
                    <div class="card-header-custom">
                        <h3 class="mb-0 text-white"><i class="bi bi-person-circle me-2"></i>Informasi Pelanggan</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            @if(auth('customer')->check())
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-person me-1"></i>Pelanggan
                                    </label>
                                    <div class="bg-light rounded p-3 border">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-person text-primary"></i>
                                            </div>
                                            <div>
                                                <strong class="d-block">{{ auth('customer')->user()->name }}</strong>
                                                <small class="text-muted">{{ auth('customer')->user()->email }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="customer_id_hidden" name="customer_id" value="{{ auth('customer')->id() }}">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="address_id" class="form-label fw-bold">
                                        <i class="bi bi-geo-alt me-1"></i>Alamat Pengiriman <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-control-custom @error('address_id') is-invalid @enderror" 
                                            id="address_id" name="address_id" required>
                                        <option value="">-- Pilih Alamat --</option>
                                        @forelse(auth('customer')->user()->addresses as $address)
                                            <option value="{{ $address->id }}" @selected(old('address_id', $address->is_default ? $address->id : null))>
                                                @if($address->label)
                                                    <strong>{{ $address->label }}</strong> - 
                                                @endif
                                                {{ Str::limit($address->address, 60) }}
                                                @if($address->is_default)
                                                    <span class="badge bg-primary ms-2">Default</span>
                                                @endif
                                            </option>
                                        @empty
                                            <option value="" disabled>Belum ada alamat terdaftar</option>
                                        @endforelse
                                    </select>
                                    @error('address_id')
                                        <div class="invalid-feedback d-flex align-items-center mt-1">
                                            <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                        </div>
                                    @enderror
                                    <div class="mt-2">
                                        <a href="{{ route('customer.addresses.index', ['from' => 'transaction']) }}" 
                                           class="text-decoration-none text-primary">
                                            <i class="bi bi-plus-circle me-1"></i>Tambah atau kelola alamat
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <label for="customer_id" class="form-label fw-bold">
                                        <i class="bi bi-person me-1"></i>Pilih Pelanggan
                                    </label>
                                    <select class="form-select form-control-custom @error('customer_id') is-invalid @enderror" 
                                            id="customer_id" name="customer_id" required>
                                        <option value="">-- Pilih Pelanggan --</option>
                                        @foreach($customers as $cust)
                                            <option value="{{ $cust->id }}" @selected(old('customer_id') == $cust->id)>
                                                {{ $cust->name }} ({{ $cust->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                        <div class="invalid-feedback d-flex align-items-center mt-1">
                                            <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Items -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-custom shadow-soft border-0">
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
        </div>

        <!-- Order Summary -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card card-custom shadow-soft border-0">
                    <div class="card-header-custom">
                        <h3 class="mb-0 text-white"><i class="bi bi-credit-card me-2"></i>Metode Pembayaran</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- QRIS Preview (Always shown) -->
                                <div id="qrisPreviewCard" class="card mt-3" style="border: 2px solid var(--primary);">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="bi bi-qr-code-scan me-2"></i>Pembayaran QRIS</h5>
                                    </div>
                                    <div class="card-body text-center">
                                        <p class="text-muted mb-3">
                                            Scan kode QR di bawah untuk melakukan pembayaran
                                        </p>
                                        
                                        <!-- QR Code Image - Larger Size -->
                                        <div class="bg-light rounded p-4 mb-4 border d-flex justify-content-center">
                                            <img id="qrisImage" src="{{ asset('qris.jpg') }}" 
                                                 alt="QRIS Code" style="width: 300px; height: 300px; object-fit: contain;">
                                        </div>
                                        
                                        <!-- Amount -->
                                        <div class="bg-success bg-opacity-10 rounded p-3 mb-4 border-start border-4 border-success">
                                            <div class="text-muted small mb-1">Total Pembayaran</div>
                                            <div class="fs-3 fw-bold text-success">
                                                Rp <span id="qrisAmount">0</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Payment Method (Fixed as QRIS) -->
                                        <input type="hidden" name="payment_method" value="qris">
                                        
                                        <!-- Instructions -->
                                        <div class="bg-warning bg-opacity-10 rounded p-3 border-start border-4 border-warning text-start">
                                            <div class="fw-bold mb-2 text-warning">
                                                <i class="bi bi-info-circle me-1"></i>Cara Pembayaran QRIS:
                                            </div>
                                            <ol class="mb-0 text-muted">
                                                <li>Buka aplikasi e-wallet atau mobile banking (GoPay, OVO, DANA, LinkAja, dll)</li>
                                                <li>Pilih fitur <strong>"Scan QRIS"</strong> atau <strong>"Bayar dengan QR"</strong></li>
                                                <li>Scan kode QR di atas dengan kamera</li>
                                                <li>Verifikasi nominal pembayaran: <strong>Rp <span id="qrisAmountInstruction">0</span></strong></li>
                                                <li>Konfirmasi dan selesaikan transaksi</li>
                                                <li>Simpan bukti pembayaran untuk verifikasi</li>
                                            </ol>
                                        </div>
                                        
                                        <!-- Info -->
                                        <div class="mt-4 pt-3 border-top">
                                            <div class="alert alert-info alert-custom">
                                                <i class="bi bi-exclamation-circle me-2"></i>
                                                <strong>Perhatian:</strong> Setelah melakukan pembayaran, silakan upload bukti pembayaran di halaman detail transaksi.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card card-custom shadow-soft border-0 h-100">
                    <div class="card-header-custom">
                        <h3 class="mb-0 text-white"><i class="bi bi-receipt me-2"></i>Ringkasan Pesanan</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Jumlah Item:</span>
                            <span class="fw-bold" id="itemCountDisplay">0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted">Total Harga:</span>
                            <h4 class="fw-bold text-primary mb-0" id="totalAmount">Rp 0</h4>
                        </div>
                        
                        <div class="bg-light rounded p-3 mb-4">
                            <small class="text-muted d-block">
                                <i class="bi bi-info-circle me-1"></i>Metode Pembayaran:
                            </small>
                            <div class="d-flex align-items-center mt-2">
                                <i class="bi bi-qr-code text-primary me-2"></i>
                                <span class="fw-bold">QRIS</span>
                            </div>
                        </div>
                        
                        <input type="hidden" id="totalInput" name="total" value="0">
                        <input type="hidden" id="itemsInput" name="items" value="[]">
                        <button type="submit" class="btn btn-primary-custom w-100 py-3">
                            <i class="bi bi-check-circle me-2"></i>Simpan Transaksi
                        </button>
                        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary w-100 mt-3">
                            <i class="bi bi-x-circle me-2"></i>Batalkan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Hidden Inputs for Form Data -->

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
    
    .item-row:hover {
        background-color: rgba(67, 97, 238, 0.05);
    }
    
    #emptyItemsState {
        display: none;
    }
    
    .form-control-custom:read-only {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }
    
    #qrisImage {
        max-width: 100%;
        height: auto;
    }
    
    @media (max-width: 768px) {
        #qrisImage {
            width: 250px !important;
            height: 250px !important;
        }
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
    document.getElementById('itemCountDisplay').textContent = itemCount;
    document.getElementById('totalAmount').textContent = `Rp ${totalAmount.toLocaleString('id-ID')}`;
    
    // Update hidden inputs
    document.getElementById('totalInput').value = totalAmount;
    document.getElementById('itemsInput').value = JSON.stringify(items);
    
    // Update QRIS amount (always visible)
    const qrisAmount = document.getElementById('qrisAmount');
    const qrisAmountInstruction = document.getElementById('qrisAmountInstruction');
    if (qrisAmount) {
        qrisAmount.textContent = totalAmount.toLocaleString('id-ID');
    }
    if (qrisAmountInstruction) {
        qrisAmountInstruction.textContent = totalAmount.toLocaleString('id-ID');
    }
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
document.getElementById('transactionForm').addEventListener('submit', function(e) {
    const itemsInput = document.getElementById('itemsInput').value;
    const items = JSON.parse(itemsInput);
    
    if (items.length === 0) {
        e.preventDefault();
        showNotification('danger', 'Tambahkan minimal 1 produk untuk membuat transaksi');
        return;
    }
    
    // Validate address for customer
    @if(auth('customer')->check())
    const addressSelect = document.getElementById('address_id');
    if (!addressSelect || !addressSelect.value) {
        e.preventDefault();
        showNotification('danger', 'Pilih alamat pengiriman terlebih dahulu');
        return;
    }
    @endif
    
    // Validate customer for admin
    @if(!auth('customer')->check())
    const customerSelect = document.getElementById('customer_id');
    if (!customerSelect || !customerSelect.value) {
        e.preventDefault();
        showNotification('danger', 'Pilih pelanggan terlebih dahulu');
        return;
    }
    @endif
    
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
    
    // Auto-add first item if it's a fresh form
    if (!sessionStorage.getItem('transactionFormState')) {
        addItem();
    }
    
    // Update QRIS amount on load
    updateTotal();
});
</script>
@endsection