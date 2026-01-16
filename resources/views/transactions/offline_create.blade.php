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

    <div class="row">
        <div class="col-md-12">
            <div class="card card-custom shadow-soft border-0">
                <div class="card-header-custom">
                    <h3 class="mb-0 text-white"><i class="bi bi-cart-plus me-2"></i>Form Transaksi Offline</h3>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger alert-custom mb-4">
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
                    @endif
                    
                    <form action="{{ route('admin.transactions.offline.store') }}" method="POST" id="offlineTransactionForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Customer Info -->
                                <div class="mb-4">
                                    <label for="customer_name" class="form-label fw-bold">
                                        <i class="bi bi-person me-1"></i>Nama Customer
                                    </label>
                                    <input type="text" class="form-control form-control-custom" 
                                           id="customer_name" name="customer_name" 
                                           placeholder="Masukkan nama customer" required>
                                </div>
                            </div>
                        </div>

                        <!-- Product Items -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="fw-bold">
                                    <i class="bi bi-list-check me-2"></i>Detail Produk
                                </h4>
                                <button type="button" class="btn btn-primary-custom" onclick="addItem()">
                                    <i class="bi bi-plus-lg me-2"></i>Tambah Item
                                </button>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-custom" id="itemsTable">
                                    <thead>
                                        <tr>
                                            <th width="40%">Produk</th>
                                            <th width="15%" class="text-center">Harga</th>
                                            <th width="15%" class="text-center">Qty</th>
                                            <th width="15%" class="text-center">Subtotal</th>
                                            <th width="15%" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Payment Summary -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label for="uang_diterima" class="form-label fw-bold">
                                        <i class="bi bi-cash-stack me-1"></i>Uang Diterima
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">Rp</span>
                                        <input type="number" class="form-control form-control-custom" 
                                               id="uang_diterima" name="uang_diterima" min="0" 
                                               placeholder="0" oninput="updateTotal()" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label for="total" class="form-label fw-bold">
                                        <i class="bi bi-calculator me-1"></i>Total
                                    </label>
                                    <div class="bg-light rounded p-3 border">
                                        <h4 class="fw-bold text-primary mb-0" id="totalDisplay">Rp 0</h4>
                                        <input type="hidden" id="total" name="total" value="0">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label for="kembalian" class="form-label fw-bold">
                                        <i class="bi bi-arrow-left-right me-1"></i>Kembalian
                                    </label>
                                    <div class="bg-light rounded p-3 border">
                                        <h4 class="fw-bold text-success mb-0" id="kembalianDisplay">Rp 0</h4>
                                        <input type="hidden" id="kembalian" name="kembalian" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="items" id="itemsInput" value="[]">

                        <div class="d-flex gap-3 mt-4 pt-4 border-top">
                            <button type="submit" class="btn btn-primary-custom flex-fill">
                                <i class="bi bi-check-circle me-2"></i>Simpan & Cetak Nota
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary flex-fill">
                                <i class="bi bi-x-circle me-2"></i>Batal
                            </a>
                        </div>
                    </form>
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
    
    .form-control-custom:read-only {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }
    
    .btn-outline-danger:hover {
        background-color: var(--danger);
        color: white;
    }
</style>

<script>
let rowCount = 0;
const products = {!! json_encode($products->map(fn($p) => [
    'id' => (int)$p->id,
    'name' => $p->name,
    'price' => (float)$p->price
])->values()) !!};

function addItem() {
    rowCount++;
    const rowId = `item_${rowCount}`;
    const row = `
        <tr id="${rowId}">
            <td>
                <select class="form-select form-control-custom item-product" 
                        data-row="${rowCount}" onchange="updateRow(${rowCount})">
                    <option value="">-- Pilih Produk --</option>
                    ${products.map(p => `
                        <option value="${p.id}" data-price="${p.price}">
                            ${p.name} - Rp ${p.price.toLocaleString('id-ID')}
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
    updateRow(rowCount);
}

function updateRow(rowNum) {
    const select = document.querySelector(`#item_${rowNum} .item-product`);
    const priceInput = document.querySelector(`#item_${rowNum} .item-price`);
    const qtyInput = document.querySelector(`#item_${rowNum} .item-qty`);
    const subtotalInput = document.querySelector(`#item_${rowNum} .item-subtotal`);
    
    if (!select || !priceInput || !qtyInput || !subtotalInput) return;
    
    const productId = select.value;
    const qty = parseInt(qtyInput.value) || 1;
    
    if (!productId) {
        priceInput.value = '0';
        subtotalInput.value = '0';
    } else {
        const product = products.find(p => p.id === parseInt(productId));
        if (product) {
            const price = product.price;
            const subtotal = price * qty;
            
            priceInput.value = price.toLocaleString('id-ID');
            subtotalInput.value = subtotal.toLocaleString('id-ID');
        }
    }
    
    updateTotal();
}

function removeRow(rowNum) {
    const row = document.getElementById(`item_${rowNum}`);
    if (row) row.remove();
    updateTotal();
}

function updateTotal() {
    let total = 0;
    let items = [];
    
    document.querySelectorAll('#itemsBody tr').forEach(row => {
        const select = row.querySelector('.item-product');
        const priceInput = row.querySelector('.item-price');
        const qtyInput = row.querySelector('.item-qty');
        const subtotalInput = row.querySelector('.item-subtotal');
        
        if (!select || !priceInput || !qtyInput || !subtotalInput) return;
        
        const productId = select.value;
        const priceText = priceInput.value.replace(/[^0-9]/g, '');
        const price = parseFloat(priceText) || 0;
        const qty = parseInt(qtyInput.value) || 1;
        const subtotalText = subtotalInput.value.replace(/[^0-9]/g, '');
        const subtotal = parseFloat(subtotalText) || 0;
        
        if (productId && price > 0 && qty > 0) {
            total += subtotal;
            items.push({ 
                product_id: parseInt(productId), 
                price: price, 
                qty: qty 
            });
        }
    });
    
    // Update display
    const totalInput = document.getElementById('total');
    const totalDisplay = document.getElementById('totalDisplay');
    const itemsInput = document.getElementById('itemsInput');
    
    totalInput.value = total.toFixed(2);
    totalDisplay.textContent = `Rp ${total.toLocaleString('id-ID')}`;
    itemsInput.value = JSON.stringify(items);
    
    // Calculate change
    const uangDiterima = parseFloat(document.getElementById('uang_diterima').value) || 0;
    const kembalianInput = document.getElementById('kembalian');
    const kembalianDisplay = document.getElementById('kembalianDisplay');
    const kembalian = uangDiterima > 0 ? (uangDiterima - total) : 0;
    
    kembalianInput.value = kembalian.toFixed(2);
    if (kembalian >= 0) {
        kembalianDisplay.textContent = `Rp ${kembalian.toLocaleString('id-ID')}`;
        kembalianDisplay.className = 'fw-bold text-success mb-0';
    } else {
        kembalianDisplay.textContent = `-Rp ${Math.abs(kembalian).toLocaleString('id-ID')}`;
        kembalianDisplay.className = 'fw-bold text-danger mb-0';
    }
}

// Event listeners
document.getElementById('itemsBody').addEventListener('change', updateTotal);
document.getElementById('itemsBody').addEventListener('input', updateTotal);
document.getElementById('uang_diterima').addEventListener('input', updateTotal);

// Form validation
document.getElementById('offlineTransactionForm').addEventListener('submit', function(e) {
    const itemsInput = document.getElementById('itemsInput');
    const items = JSON.parse(itemsInput.value);
    
    if (items.length === 0) {
        e.preventDefault();
        alert('⚠️ Tambahkan minimal 1 produk untuk membuat transaksi');
        return;
    }
    
    const uangDiterima = parseFloat(document.getElementById('uang_diterima').value) || 0;
    const total = parseFloat(document.getElementById('total').value) || 0;
    
    if (uangDiterima < total) {
        e.preventDefault();
        alert('⚠️ Uang diterima kurang dari total transaksi');
        return;
    }
});

// Initialize first item
document.addEventListener('DOMContentLoaded', function() {
    addItem();
});
</script>
@endsection