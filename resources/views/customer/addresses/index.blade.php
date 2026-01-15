@extends('layouts.app')

@section('title', 'Kelola Alamat')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Alamat Saya</h2>
                @if(request('from') === 'transaction')
                    <a href="{{ route('transactions.create') }}" class="btn btn-secondary btn-sm">
                        ← Kembali ke Transaksi
                    </a>
                @endif
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="mb-3">
                <a href="{{ route('customer.addresses.create', request('from') ? ['from' => request('from')] : []) }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Alamat Baru
                </a>
            </div>

            @forelse($addresses as $address)
                <div class="card mb-3 @if($address->is_default) border-primary @endif">
                    <div class="card-body">
                        <div class="row align-items-start">
                            <div class="col">
                                <h5 class="card-title">
                                    @if($address->label)
                                        {{ $address->label }}
                                    @else
                                        Alamat
                                    @endif
                                    @if($address->is_default)
                                        <span class="badge bg-primary">Default</span>
                                    @endif
                                </h5>
                                <p class="card-text">{{ $address->address }}</p>
                                @if($address->phone)
                                    <p class="card-text text-muted">
                                        <i class="bi bi-telephone"></i> {{ $address->phone }}
                                    </p>
                                @endif
                            </div>
                            <div class="col-auto">
                                @if(!$address->is_default)
                                    <form action="{{ route('customer.addresses.setDefault', $address) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary" 
                                                onclick="setDefaultAddress(event, this)">
                                            Set Default
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('customer.addresses.edit', $address) }}{{ request('from') ? '?from=' . request('from') : '' }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                <form action="{{ route('customer.addresses.destroy', $address) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Yakin hapus alamat ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    Belum ada alamat. <a href="{{ route('customer.addresses.create') }}">Tambah alamat pertama Anda</a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
function setDefaultAddress(event, button) {
    event.preventDefault();
    
    if (!confirm('Jadikan alamat ini default?')) {
        return false;
    }
    
    const form = button.closest('form');
    const fromTransaction = new URLSearchParams(window.location.search).get('from') === 'transaction';
    const actionUrl = fromTransaction ? form.action + '?from=transaction' : form.action;
    
    const formData = new FormData(form);
    
    fetch(actionUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message);
            // Jika dari transaction, gunakan history.back() agar form tetap preserved
            if (new URLSearchParams(window.location.search).get('from') === 'transaction') {
                setTimeout(() => {
                    history.back();
                }, 500);
            } else if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                location.reload();
            }
        } else {
            alert('❌ ' + (data.message || 'Gagal mengubah alamat default'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan. Silakan coba lagi.');
    });
    
    return false;
}
</script>
@endsection
