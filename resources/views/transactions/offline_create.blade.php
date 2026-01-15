@extends('layouts.app')

@section('title', 'Transaksi Offline (Kasir)')

@section('content')
<div class="row">
    <div class="col-md-10">
        <h2>Transaksi Offline (Kasir)</h2>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admin.transactions.offline.store') }}" method="POST" id="offlineTransactionForm">
            @csrf
            <div class="mb-3">
                <label for="customer_name" class="form-label">Nama Customer</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
            </div>
            <h4>Detail Produk</h4>
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
            <div class="mb-3">
                <label for="uang_diterima" class="form-label">Uang Diterima</label>
                <input type="number" class="form-control" id="uang_diterima" name="uang_diterima" min="0" required>
            </div>
            <div class="mb-3">
                <label for="total" class="form-label">Total</label>
                <input type="text" class="form-control" id="total" name="total" readonly>
            </div>
            <div class="mb-3">
                <label for="kembalian" class="form-label">Kembalian</label>
                <input type="text" class="form-control" id="kembalian" name="kembalian" readonly>
            </div>
            <input type="hidden" name="items" id="itemsInput">
            <button type="submit" class="btn btn-primary">Simpan & Cetak Nota</button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
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
        priceInput.value = 0;
        subtotalInput.value = 0;
    } else {
        const product = products.find(p => p.id === parseInt(productId));
        if (product) {
            const price = product.price;
            priceInput.value = price;
            subtotalInput.value = (price * qty).toFixed(2);
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
        const price = parseFloat(priceInput.value) || 0;
        const qty = parseInt(qtyInput.value) || 1;
        const subtotal = parseFloat(subtotalInput.value) || 0;
        if (productId && price > 0 && qty > 0) {
            total += subtotal;
            items.push({ product_id: parseInt(productId), price, qty });
        }
    });
    document.getElementById('total').value = total.toFixed(2);
    document.getElementById('itemsInput').value = JSON.stringify(items);
    // Hitung kembalian
    const uangDiterima = parseFloat(document.getElementById('uang_diterima').value) || 0;
    document.getElementById('kembalian').value = uangDiterima > 0 ? (uangDiterima - total).toFixed(2) : '';
}

document.getElementById('itemsBody').addEventListener('change', updateTotal);
document.getElementById('itemsBody').addEventListener('input', updateTotal);
document.getElementById('uang_diterima').addEventListener('input', updateTotal);
</script>
@endsection
