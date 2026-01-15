<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CustomerAddressController extends Controller
{
    /**
     * Tampilkan daftar alamat customer
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $addresses = $customer->addresses()->orderBy('is_default', 'desc')->get();
        
        return view('customer.addresses.index', compact('addresses'));
    }

    /**
     * Form tambah alamat
     */
    public function create()
    {
        return view('customer.addresses.create');
    }

    /**
     * Simpan alamat baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'nullable|string|max:50',
            'address' => 'required|string|min:10',
            'phone' => 'nullable|string|max:15',
            'is_default' => 'boolean',
        ]);

        $customer = Auth::guard('customer')->user();
        
        // Jika ada alamat yang dijadiin default, hapus default sebelumnya
        if ($request->boolean('is_default')) {
            $customer->addresses()->update(['is_default' => false]);
        }

        // Jika ini alamat pertama, jadiin default otomatis
        if ($customer->addresses()->count() === 0) {
            $request->merge(['is_default' => true]);
        }

        $customer->addresses()->create($request->all());

        // Kembali ke halaman sebelumnya jika dari transaksi
        if ($request->query('from') === 'transaction') {
            return redirect()->back()->with('success', 'Alamat berhasil ditambahkan');
        }

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Alamat berhasil ditambahkan');
    }

    /**
     * Form edit alamat
     */
    public function edit(CustomerAddress $address)
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer || $address->customer_id !== $customer->id) {
            abort(403, 'Unauthorized');
        }
        return view('customer.addresses.edit', compact('address'));
    }

    /**
     * Update alamat
     */
    public function update(Request $request, CustomerAddress $address)
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer || $address->customer_id !== $customer->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'label' => 'nullable|string|max:50',
            'address' => 'required|string|min:10',
            'phone' => 'nullable|string|max:15',
            'is_default' => 'boolean',
        ]);

        // Jika jadiin default, hapus default sebelumnya
        if ($request->boolean('is_default')) {
            $address->customer->addresses()->update(['is_default' => false]);
        }

        $address->update($request->all());

        // Kembali ke halaman sebelumnya jika dari transaksi
        if ($request->query('from') === 'transaction') {
            return redirect()->back()->with('success', 'Alamat berhasil diperbarui');
        }

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Alamat berhasil diperbarui');
    }

    /**
     * Hapus alamat
     */
    public function destroy(CustomerAddress $address)
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer || $address->customer_id !== $customer->id) {
            abort(403, 'Unauthorized');
        }

        // Jika yang dihapus adalah default address
        if ($address->is_default) {
            $firstAddress = $address->customer->addresses()
                ->where('id', '!=', $address->id)
                ->first();
            
            if ($firstAddress) {
                $firstAddress->update(['is_default' => true]);
            }
        }

        $address->forceDelete();

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Alamat berhasil dihapus');
    }

    /**
     * Set alamat sebagai default (AJAX)
     */
    public function setDefault(CustomerAddress $address)
    {
        // Validasi customer
        $customer = Auth::guard('customer')->user();
        if (!$customer || $address->customer_id !== $customer->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Update semua address default menjadi false
        $address->customer->addresses()->update(['is_default' => false]);
        
        // Set address ini menjadi default
        $address->update(['is_default' => true]);

        return response()->json(['success' => true, 'message' => 'Alamat default berhasil diubah']);
    }
}
