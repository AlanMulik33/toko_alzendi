@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>Tambah Transaksi</h2>
        
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('transactions.store') }}" method="POST" id="transactionForm">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Pelanggan</label>
                        @if(auth('customer')->check())
                            <input type="text" class="form-control" value="{{ auth('customer')->user()->name }}" readonly>
                            <input type="hidden" id="customer_id_hidden" name="customer_id" value="{{ auth('customer')->id() }}">
                        @else
                            <select class="form-control @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                                <option value="">-- Pilih Pelanggan --</option>
                                @foreach($customers as $cust)
                                <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                                @endforeach
                            </select>
                            @error('customer_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        @endif
                    </div>
                </div>
            </div>

            <h4>Detail Transaksi</h4>
            <table class="table table-bordered" id="itemsTable">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="itemsBody">
                </tbody>
            </table>

            <button type="button" class="btn btn-success mb-3" onclick="addItem()">Tambah Item</button>

            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5>Ringkasan</h5>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Jumlah Item:</strong></td>
                                    <td><span id="itemCountDisplay">0</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Total Harga:</strong></td>
                                    <td><strong>Rp <span id="totalAmount">0</span></strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="paymentCash" value="cash" checked>
                            <label class="form-check-label" for="paymentCash">
                                💵 Cash (Tunai)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="paymentQris" value="qris">
                            <label class="form-check-label" for="paymentQris">
                                📱 QRIS
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" id="totalInput" name="total" value="0">
            <input type="hidden" id="itemsInput" name="items" value="[]">

            <button type="submit" class="btn btn-primary mt-3">Simpan Transaksi</button>
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary mt-3">Batal</a>
        </form>
    </div>
</div>

<script>
let rowCount = 0;
// Store products dengan harga yang sudah di-convert ke number
const products = {!! json_encode($products->map(fn($p) => [
    'id' => (int)$p->id, 
    'name' => $p->name, 
    'price' => (float)$p->price
])->values()) !!};

console.log('=== PRODUCTS LOADED ===');
console.log(products);

// Track item data
const itemsData = {};

function addItem() {
    rowCount++;
    const rowId = `item_${rowCount}`;
    
    const row = `
        <tr id="${rowId}">
            <td>
                <select class="form-control item-product" data-row="${rowCount}" onchange="updateRow(${rowCount})">
                    <option value="">-- Pilih Produk --</option>
                    ${products.map(p => `<option value="${p.id}" data-price="${p.price}">${p.name}</option>`).join('')}
                </select>
            </td>
            <td><input type="number" class="form-control item-price" value="0" step="0.01" readonly></td>
            <td><input type="number" class="form-control item-qty" value="1" min="1" max="999" onchange="updateRow(${rowCount})" oninput="updateRow(${rowCount})"></td>
            <td><input type="number" class="form-control item-subtotal" value="0" step="0.01" readonly></td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(${rowCount})">Hapus</button>
            </td>
        </tr>
    `;
    
    document.getElementById('itemsBody').insertAdjacentHTML('beforeend', row);
    console.log(`✓ Row ${rowCount} added`);
    updateRow(rowCount);
}

function updateRow(rowNum) {
    const select = document.querySelector(`#item_${rowNum} .item-product`);
    const priceInput = document.querySelector(`#item_${rowNum} .item-price`);
    const qtyInput = document.querySelector(`#item_${rowNum} .item-qty`);
    const subtotalInput = document.querySelector(`#item_${rowNum} .item-subtotal`);
    
    if (!select || !priceInput || !qtyInput || !subtotalInput) {
        console.error(`Row ${rowNum} elements not found`);
        return;
    }
    
    // Get values
    const productId = select.value;
    const qty = parseInt(qtyInput.value) || 1;
    
    if (!productId) {
        priceInput.value = 0;
        subtotalInput.value = 0;
    } else {
        // Find product
        const product = products.find(p => p.id === parseInt(productId));
        if (product) {
            const price = product.price;
            const subtotal = price * qty;
            
            priceInput.value = parseFloat(price).toFixed(2);
            subtotalInput.value = parseFloat(subtotal).toFixed(2);
            
            console.log(`Row ${rowNum}: ${product.name} | Price: ${price} | Qty: ${qty} | Subtotal: ${subtotal}`);
        }
    }
    
    updateTotal();
}

function removeRow(rowNum) {
    const row = document.getElementById(`item_${rowNum}`);
    if (row) {
        row.remove();
        console.log(`✗ Row ${rowNum} removed`);
    }
    updateTotal();
}

function updateTotal() {
    let totalAmount = 0;
    let itemCount = 0;
    const items = [];
    
    // Loop semua rows
    let rowNum = 1;
    while (document.getElementById(`item_${rowNum}`)) {
        const select = document.querySelector(`#item_${rowNum} .item-product`);
        const price = parseFloat(document.querySelector(`#item_${rowNum} .item-price`).value) || 0;
        const qty = parseInt(document.querySelector(`#item_${rowNum} .item-qty`).value) || 0;
        const subtotal = parseFloat(document.querySelector(`#item_${rowNum} .item-subtotal`).value) || 0;
        
        if (select.value && qty > 0 && price > 0 && subtotal > 0) {
            itemCount++;
            totalAmount += subtotal;
            
            items.push({
                product_id: parseInt(select.value),
                qty: qty,
                price: price
            });
        }
        
        rowNum++;
    }
    
    // Update display
    const formatter = new Intl.NumberFormat('id-ID');
    document.getElementById('totalAmount').textContent = formatter.format(Math.round(totalAmount));
    document.getElementById('itemCountDisplay').textContent = itemCount;
    document.getElementById('totalInput').value = parseFloat(totalAmount).toFixed(2);
    document.getElementById('itemsInput').value = JSON.stringify(items);
    
    console.log(`📊 Updated: ${itemCount} items, Total: ${totalAmount}`);
    console.log(items);
}

// Form submit validation
document.getElementById('transactionForm').addEventListener('submit', function(e) {
    let customerValue = '';
    @if(auth('customer')->check())
        customerValue = document.getElementById('customer_id_hidden').value;
    @else
        customerValue = document.getElementById('customer_id').value;
    @endif
    
    const itemsInput = document.getElementById('itemsInput').value;
    const totalInput = document.getElementById('totalInput').value;
    
    console.log('=== FORM SUBMIT ===');
    console.log('Customer:', customerValue);
    console.log('Total:', totalInput);
    console.log('Items:', itemsInput);
    
    // Validasi customer
    if (!customerValue) {
        e.preventDefault();
        alert('❌ Pilih pelanggan terlebih dahulu');
        return;
    }
    
    // Validasi items
    let items = [];
    try {
        items = JSON.parse(itemsInput);
    } catch (err) {
        e.preventDefault();
        alert('Error: Invalid items data');
        return;
    }
    
    if (items.length === 0) {
        e.preventDefault();
        alert('❌ Tambahkan minimal 1 item\n\nCara:\n1. Klik "Tambah Item"\n2. Pilih produk di dropdown\n3. Atur jumlah/qty\n4. Harga akan otomatis ter-hitung');
        return;
    }
    
    // Validasi total
    const total = parseFloat(totalInput);
    if (total <= 0) {
        e.preventDefault();
        alert(`❌ Total masih 0\n\nData yang ter-kirim:\n- Items: ${items.length}\n- Total: ${total}\n\nCek di console (F12) untuk debug`);
        console.error('Items yang ter-kirim:', items);
        console.error('Total yang ter-kirim:', total);
        return;
    }
    
    console.log('✅ Validation passed - submitting');
});
</script>
@endsection
