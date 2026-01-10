@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>Tambah Transaksi</h2>
        <form action="{{ route('transactions.store') }}" method="POST" id="transactionForm">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Pelanggan</label>
                        <select class="form-control @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($customers as $cust)
                            <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                            @endforeach
                        </select>
                        @error('customer_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
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

            <div class="row">
                <div class="col-md-4 offset-md-8">
                    <div class="alert alert-info">
                        <strong>Total: Rp <span id="totalAmount">0</span></strong>
                    </div>
                </div>
            </div>

            <input type="hidden" id="totalInput" name="total" value="0">
            <input type="hidden" id="itemsInput" name="items" value="[]">

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<script>
let rowCount = 0;
const products = {!! json_encode($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'price' => (float)$p->price])->values()) !!};

function addItem() {
    rowCount++;
    const row = document.createElement('tr');
    row.id = 'row_' + rowCount;
    row.innerHTML = `
        <td>
            <select class="form-control product-select" data-row="${rowCount}" onchange="updateSubtotal(${rowCount})">
                <option value="">-- Pilih Produk --</option>
                ${products.map(p => `<option value="${p.id}" data-price="${p.price}">${p.name} (Rp ${parseFloat(p.price).toLocaleString('id-ID')})</option>`).join('')}
            </select>
        </td>
        <td><input type="number" class="form-control price-input price-${rowCount}" readonly></td>
        <td><input type="number" class="form-control qty-input qty-${rowCount}" value="1" min="1" onchange="updateSubtotal(${rowCount})"></td>
        <td><input type="number" class="form-control subtotal-input subtotal-${rowCount}" readonly></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="removeItem(${rowCount})">Hapus</button></td>
    `;
    document.getElementById('itemsBody').appendChild(row);
}

function updateSubtotal(rowNum) {
    const select = document.querySelector(`#row_${rowNum} .product-select`);
    if (!select.value) {
        document.querySelector(`.price-${rowNum}`).value = 0;
        document.querySelector(`.subtotal-${rowNum}`).value = 0;
        calculateTotal();
        return;
    }
    
    const price = parseFloat(select.options[select.selectedIndex].dataset.price) || 0;
    const qty = parseFloat(document.querySelector(`.qty-${rowNum}`).value) || 0;
    const subtotal = price * qty;

    document.querySelector(`.price-${rowNum}`).value = price;
    document.querySelector(`.subtotal-${rowNum}`).value = subtotal;

    calculateTotal();
}

function removeItem(rowNum) {
    const row = document.getElementById('row_' + rowNum);
    if(row) {
        row.remove();
    }
    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    
    // Hitung semua subtotal
    document.querySelectorAll('[class^="subtotal-"]').forEach(el => {
        const value = parseFloat(el.value) || 0;
        if(value > 0) {
            total += value;
        }
    });

    // Update tampilan dengan format Rupiah
    const formatter = new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    document.getElementById('totalAmount').textContent = formatter.format(total);
    document.getElementById('totalInput').value = total;

    // Kumpulkan items untuk submit
    const items = [];
    let rowNum = 1;
    while(document.querySelector(`.qty-${rowNum}`)) {
        const select = document.querySelector(`#row_${rowNum} .product-select`);
        const qty = document.querySelector(`.qty-${rowNum}`).value;
        const price = document.querySelector(`.price-${rowNum}`).value;
        
        if(select && select.value && qty > 0 && price > 0) {
            items.push({
                product_id: parseInt(select.value),
                qty: parseInt(qty),
                price: parseFloat(price)
            });
        }
        rowNum++;
    }

    document.getElementById('itemsInput').value = JSON.stringify(items);
    
    // Debug
    console.log('Items count:', items.length);
    console.log('Total calculated:', total);
    console.log('Items:', items);
}

document.getElementById('transactionForm').addEventListener('submit', function(e) {
    const itemsInput = document.getElementById('itemsInput').value;
    const items = JSON.parse(itemsInput);
    const total = parseFloat(document.getElementById('totalInput').value);
    
    if(items.length === 0) {
        e.preventDefault();
        alert('Tambahkan minimal 1 item');
        return;
    }
    
    if(total <= 0) {
        e.preventDefault();
        alert('Total harus lebih dari 0');
        return;
    }
});
</script>
@endsection
