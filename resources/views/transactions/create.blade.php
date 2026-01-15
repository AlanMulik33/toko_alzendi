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
                
                @if(auth('customer')->check())
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="address_id" class="form-label">Alamat Pengiriman <span class="text-danger">*</span></label>
                        <select class="form-control @error('address_id') is-invalid @enderror" id="address_id" name="address_id" required>
                            <option value="">-- Pilih Alamat --</option>
                            @forelse(auth('customer')->user()->addresses as $address)
                                <option value="{{ $address->id }}" @selected(old('address_id', $address->is_default ? $address->id : null))>
                                    @if($address->label){{ $address->label }} - @endif{{ substr($address->address, 0, 50) }}{{ strlen($address->address) > 50 ? '...' : '' }}
                                    @if($address->is_default)<span class="badge bg-primary">Default</span>@endif
                                </option>
                            @empty
                                <option value="" disabled>Tambahkan alamat di profil terlebih dahulu</option>
                            @endforelse
                        </select>
                        <small class="text-muted">
                            <a href="{{ route('customer.addresses.index', ['from' => 'transaction']) }}">Kelola alamat</a>
                        </small>
                        @error('address_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                    </div>
                </div>
                @endif
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
                            <input class="form-check-input" type="radio" name="payment_method" id="paymentCash" value="cash" checked onchange="updatePaymentPreview()">
                            <label class="form-check-label" for="paymentCash">
                                💵 Cash (Tunai)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="paymentQris" value="qris" onchange="updatePaymentPreview()">
                            <label class="form-check-label" for="paymentQris">
                                📱 QRIS
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QRIS Preview Card -->
            <div id="qrisPreviewCard" class="card mt-4" style="display: none; border: 2px solid #17a2b8;">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">📱 QRIS Payment Instructions</h5>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted mb-3">
                        Scan QR code di bawah untuk melakukan pembayaran ke Toko Alzendi
                    </p>
                    
                    <!-- QR Code Image -->
                    <div style="margin: 20px 0; padding: 20px; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6;">
                        <img id="qrisImage" src="{{ route('qris.image') }}" alt="QRIS Code" style="width: 280px; height: 280px; object-fit: contain;">
                    </div>
                    
                    <!-- Nominal -->
                    <div style="margin: 15px 0; padding: 15px; background-color: #e8f5e9; border-radius: 8px; border-left: 4px solid #4caf50;">
                        <div style="font-size: 12px; color: #666; margin-bottom: 5px;">Total Nominal Pembayaran</div>
                        <div style="font-size: 24px; font-weight: bold; color: #2e7d32;">
                            Rp <span id="qrisAmount">0</span>
                        </div>
                    </div>
                    
                    <!-- Download Button -->
                    <div style="margin-top: 15px;">
                        <a href="{{ route('qris.download') }}" class="btn btn-sm btn-outline-info" target="_blank">
                            📥 Download QRIS Code
                        </a>
                    </div>
                    
                    <!-- Instructions -->
                    <div style="margin-top: 20px; padding: 15px; background-color: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107; text-align: left;">
                        <div style="font-weight: bold; margin-bottom: 8px; color: #856404;">📋 Cara Pembayaran:</div>
                        <ol style="margin-bottom: 0; color: #856404; font-size: 13px;">
                            <li>Buka aplikasi e-wallet atau banking Anda</li>
                            <li>Pilih fitur "Scan QRIS"</li>
                            <li>Scan QR code di atas</li>
                            <li>Verifikasi nominal: <strong>Rp <span id="qrisAmountInstruction">0</span></strong></li>
                            <li>Konfirmasi dan selesaikan pembayaran</li>
                            <li>Simpan bukti pembayaran</li>
                        </ol>
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
    
    // Update QRIS preview amount jika QRIS dipilih
    if (document.getElementById('paymentQris').checked) {
        document.getElementById('qrisAmount').textContent = formatter.format(Math.round(totalAmount));
        document.getElementById('qrisAmountInstruction').textContent = formatter.format(Math.round(totalAmount));
    }
    
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

// Update payment method preview
function updatePaymentPreview() {
    const isQris = document.getElementById('paymentQris').checked;
    const qrisPreviewCard = document.getElementById('qrisPreviewCard');
    const totalAmount = parseFloat(document.getElementById('totalInput').value) || 0;
    const formatter = new Intl.NumberFormat('id-ID');
    
    if (isQris) {
        qrisPreviewCard.style.display = 'block';
        document.getElementById('qrisAmount').textContent = formatter.format(Math.round(totalAmount));
        document.getElementById('qrisAmountInstruction').textContent = formatter.format(Math.round(totalAmount));
        console.log('📱 QRIS selected - preview shown');
    } else {
        qrisPreviewCard.style.display = 'none';
        console.log('💵 Cash selected - QRIS hidden');
    }
}

// ===== SAVE & RESTORE FORM STATE =====
// Simpan state form saat ada perubahan
function saveFormState() {
    const formState = {
        items: document.getElementById('itemsInput').value,
        total: document.getElementById('totalInput').value,
        itemCount: document.getElementById('itemCountDisplay').textContent,
        totalAmount: document.getElementById('totalAmount').textContent,
        paymentMethod: document.querySelector('input[name="payment_method"]:checked').value,
        addressId: document.getElementById('address_id') ? document.getElementById('address_id').value : null,
        timestamp: new Date().getTime()
    };
    sessionStorage.setItem('transactionFormState', JSON.stringify(formState));
    console.log('💾 Form state saved:', formState);
}

// Restore state form jika ada
function restoreFormState() {
    const savedState = sessionStorage.getItem('transactionFormState');
    if (!savedState) return;

    try {
        const formState = JSON.parse(savedState);
        const timeSinceLastSave = new Date().getTime() - formState.timestamp;
        
        // Hanya restore jika kurang dari 1 jam yang lalu
        if (timeSinceLastSave > 3600000) {
            console.log('⏰ Form state expired');
            sessionStorage.removeItem('transactionFormState');
            return;
        }

        // Restore items dan total
        if (formState.items && formState.items !== '[]') {
            const items = JSON.parse(formState.items);
            
            // Recreate rows
            items.forEach((item, index) => {
                rowCount++;
                const rowId = 'item_' + rowCount;
                
                const row = `
                    <tr id="${rowId}">
                        <td>
                            <select class="form-control item-product" data-row="${rowCount}" onchange="updateRow(${rowCount})">
                                <option value="">-- Pilih Produk --</option>
                                ${products.map(p => {
                                    const selected = p.id === item.product_id ? 'selected' : '';
                                    return `<option value="${p.id}" data-price="${p.price}" ${selected}>${p.name}</option>`;
                                }).join('')}
                            </select>
                        </td>
                        <td><input type="number" class="form-control item-price" value="${item.price}" step="0.01" readonly></td>
                        <td><input type="number" class="form-control item-qty" value="${item.qty}" min="1" max="999" onchange="updateRow(${rowCount})" oninput="updateRow(${rowCount})"></td>
                        <td><input type="number" class="form-control item-subtotal" value="${(item.price * item.qty).toFixed(2)}" step="0.01" readonly></td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(${rowCount})">Hapus</button>
                        </td>
                    </tr>
                `;
                
                document.getElementById('itemsBody').insertAdjacentHTML('beforeend', row);
            });

            // Restore totals
            document.getElementById('itemCountDisplay').textContent = formState.itemCount;
            document.getElementById('totalAmount').textContent = formState.totalAmount;
            document.getElementById('totalInput').value = formState.total;
            document.getElementById('itemsInput').value = formState.items;

            // Restore payment method
            const paymentRadio = document.querySelector(`input[name="payment_method"][value="${formState.paymentMethod}"]`);
            if (paymentRadio) {
                paymentRadio.checked = true;
                updatePaymentPreview();
            }

            // Restore address
            if (formState.addressId && document.getElementById('address_id')) {
                document.getElementById('address_id').value = formState.addressId;
            }

            console.log('✅ Form state restored:', formState);
        }

        // Clear state after restore
        sessionStorage.removeItem('transactionFormState');
    } catch (error) {
        console.error('Error restoring form state:', error);
        sessionStorage.removeItem('transactionFormState');
    }
}

// Restore saat page load
document.addEventListener('DOMContentLoaded', function() {
    restoreFormState();
});

// Save state saat ada perubahan di form
document.getElementById('itemsBody').addEventListener('change', saveFormState);
document.getElementById('itemsBody').addEventListener('input', saveFormState);
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', saveFormState);
});
if (document.getElementById('address_id')) {
    document.getElementById('address_id').addEventListener('change', saveFormState);
}

// Save state sebelum navigasi ke kelola alamat
document.querySelectorAll('a[href*="customer/addresses"]').forEach(link => {
    link.addEventListener('click', function() {
        saveFormState();
    });
});
</script>
@endsection
