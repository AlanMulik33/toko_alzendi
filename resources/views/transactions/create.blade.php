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
const products = {!! json_encode($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'price' => $p->price])->values()) !!};

function addItem() {
    rowCount++;
    const row = document.createElement('tr');
    row.id = 'row_' + rowCount;
    row.innerHTML = `
        <td>
            <select class="form-control product-select" onchange="updateSubtotal(${rowCount})">
                <option value="">-- Pilih Produk --</option>
                ${products.map(p => `<option value="${p.id}" data-price="${p.price}">${p.name} (Rp ${p.price.toLocaleString()})</option>`).join('')}
            </select>
        </td>
        <td><input type="number" class="form-control price-${rowCount}" readonly></td>
        <td><input type="number" class="form-control qty-${rowCount}" value="1" min="1" onchange="updateSubtotal(${rowCount})"></td>
        <td><input type="number" class="form-control subtotal-${rowCount}" readonly></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="removeItem(${rowCount})">Hapus</button></td>
    `;
    document.getElementById('itemsBody').appendChild(row);
}

function updateSubtotal(rowNum) {
    const select = document.querySelector(`#row_${rowNum} .product-select`);
    const price = select.options[select.selectedIndex].dataset.price || 0;
    const qty = document.querySelector(`.qty-${rowNum}`).value;
    const subtotal = price * qty;

    document.querySelector(`.price-${rowNum}`).value = price;
    document.querySelector(`.subtotal-${rowNum}`).value = subtotal;

    calculateTotal();
}

function removeItem(rowNum) {
    document.getElementById('row_' + rowNum).remove();
    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    document.querySelectorAll('.subtotal-' + document.querySelectorAll('tr[id^="row_"]').length + ', [class^="subtotal-"]').forEach(el => {
        if(el.value) total += parseFloat(el.value);
    });

    for(let i = 1; i <= rowCount; i++) {
        const elem = document.querySelector(`.subtotal-${i}`);
        if(elem && elem.value) total += parseFloat(elem.value);
    }

    total = 0;
    document.querySelectorAll('[class^="subtotal-"]').forEach(el => {
        if(el.value) total += parseFloat(el.value);
    });

    document.getElementById('totalAmount').textContent = total.toLocaleString();
    document.getElementById('totalInput').value = total;

    const items = [];
    document.querySelectorAll('tr[id^="row_"]').forEach(row => {
        const select = row.querySelector('.product-select');
        const qty = row.querySelector('[class^="qty-"]').value;
        const price = row.querySelector('[class^="price-"]').value;
        
        if(select.value) {
            items.push({
                product_id: select.value,
                qty: qty,
                price: price
            });
        }
    });

    document.getElementById('itemsInput').value = JSON.stringify(items);
}

document.getElementById('transactionForm').addEventListener('submit', function(e) {
    if(document.querySelectorAll('tr[id^="row_"]').length === 0) {
        e.preventDefault();
        alert('Tambahkan minimal 1 item');
        return;
    }
});
</script>
@endsection
