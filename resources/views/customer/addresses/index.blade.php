@extends('layouts.app')

@section('title', 'Kelola Alamat')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0 overflow-hidden">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 60px; height: 60px;">
                            <i class="bi bi-geo-alt text-white" style="font-size: 1.8rem;"></i>
                        </div>
                        <div>
                            <h1 class="h2 mb-1 text-white">Alamat Saya</h1>
                            <p class="mb-0 text-white opacity-75">
                                <i class="bi bi-house-check me-1"></i>Kelola alamat pengiriman Anda
                            </p>
                        </div>
                    </div>
                    @if(request('from') === 'transaction')
                    <a href="{{ route('transactions.create') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Transaksi
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success alert-custom">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0 text-white"><i class="bi bi-list-ul me-2"></i>Daftar Alamat</h3>
                        <small class="text-white opacity-75">Total {{ $addresses->count() }} alamat</small>
                    </div>
                    <a href="{{ route('customer.addresses.create', request('from') ? ['from' => request('from')] : []) }}" 
                       class="btn btn-light">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Alamat Baru
                    </a>
                </div>
                
                <div class="card-body p-4">
                    @if($addresses->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-geo-alt" style="font-size: 3rem; color: var(--gray);"></i>
                            <h4 class="mt-3 text-muted">Belum ada alamat</h4>
                            <p class="text-muted">Tambahkan alamat pertama untuk pengiriman produk</p>
                            <a href="{{ route('customer.addresses.create', request('from') ? ['from' => request('from')] : []) }}" 
                               class="btn btn-primary-custom mt-2">
                                <i class="bi bi-plus-lg me-2"></i>Tambah Alamat Pertama
                            </a>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($addresses as $address)
                            <div class="col-md-6">
                                <div class="card card-custom shadow-sm h-100 border-0 @if($address->is_default) border-primary border-2 @endif">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h5 class="fw-bold mb-1">
                                                    @if($address->label)
                                                        <i class="bi bi-tag me-2 text-primary"></i>{{ $address->label }}
                                                    @else
                                                        <i class="bi bi-geo-alt me-2 text-primary"></i>Alamat
                                                    @endif
                                                </h5>
                                                @if($address->is_default)
                                                <span class="badge bg-primary rounded-pill px-3 py-2 mt-2">
                                                    <i class="bi bi-star-fill me-1"></i>Default
                                                </span>
                                                @endif
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary btn-sm" type="button" 
                                                        data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if(!$address->is_default)
                                                    <li>
                                                        <form action="{{ route('customer.addresses.setDefault', $address) }}" 
                                                              method="POST" class="dropdown-item-form">
                                                            @csrf
                                                            <input type="hidden" name="from" value="{{ request('from') }}">
                                                            <button type="submit" class="dropdown-item" 
                                                                    onclick="setDefaultAddress(event, this)">
                                                                <i class="bi bi-star me-2"></i>Set Default
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endif
                                                    <li>
                                                        <a class="dropdown-item" 
                                                           href="{{ route('customer.addresses.edit', $address) }}{{ request('from') ? '?from=' . request('from') : '' }}">
                                                            <i class="bi bi-pencil me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('customer.addresses.destroy', $address) }}" 
                                                              method="POST" class="dropdown-item-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="from" value="{{ request('from') }}">
                                                            <button type="submit" class="dropdown-item text-danger" 
                                                                    onclick="return confirm('Yakin ingin menghapus alamat ini?')">
                                                                <i class="bi bi-trash me-2"></i>Hapus
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <p class="text-muted mb-0">
                                                <i class="bi bi-geo-alt me-2"></i>
                                                {{ $address->address }}
                                            </p>
                                        </div>
                                        
                                        @if($address->phone)
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="bi bi-telephone me-2"></i>
                                            <span>{{ $address->phone }}</span>
                                        </div>
                                        @endif
                                        
                                        @if($address->is_default)
                                        <div class="mt-4 pt-3 border-top">
                                            <small class="text-success">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Alamat default untuk pengiriman
                                            </small>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-primary {
        border-color: var(--primary) !important;
    }
    
    .card.shadow-sm {
        transition: transform 0.3s ease;
    }
    
    .card.shadow-sm:hover {
        transform: translateY(-5px);
    }
    
    .dropdown-item-form {
        margin: 0;
    }
    
    .dropdown-item {
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .dropdown-item:hover {
        background-color: rgba(67, 97, 238, 0.1);
    }
    
    .badge.rounded-pill {
        padding: 0.5rem 1rem;
    }
</style>

<script>
function setDefaultAddress(event, button) {
    event.preventDefault();
    
    if (!confirm('Jadikan alamat ini sebagai default?')) {
        return false;
    }
    
    const form = button.closest('form');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            
            // Reload after delay
            setTimeout(() => {
                if (new URLSearchParams(window.location.search).get('from') === 'transaction') {
                    // Return to transaction page if coming from transaction
                    window.location.href = "{{ route('transactions.create') }}";
                } else {
                    location.reload();
                }
            }, 1000);
        } else {
            showNotification('error', data.message || 'Gagal mengubah alamat default');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Terjadi kesalahan. Silakan coba lagi.');
    });
    
    return false;
}

function showNotification(type, message) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-custom alert-dismissible fade show`;
    alert.innerHTML = `
        <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}-fill me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container-fluid');
    const existingAlert = container.querySelector('.alert');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    container.insertBefore(alert, container.children[1]);
    
    setTimeout(() => {
        alert.remove();
    }, 5000);
}

// Handle delete confirmation with better UX
document.querySelectorAll('form[action*="destroy"]').forEach(form => {
    const deleteBtn = form.querySelector('button[type="submit"]');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            if (!confirm('Yakin ingin menghapus alamat ini?')) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    }
});
</script>
@endsection