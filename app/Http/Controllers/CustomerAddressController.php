<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Alamat berhasil ditambahkan');
    }

    /**
     * Form edit alamat
     */
    public function edit(CustomerAddress $address)
    {
        $this->authorize('view', $address);
        return view('customer.addresses.edit', compact('address'));
    }

    /**
     * Update alamat
     */
    public function update(Request $request, CustomerAddress $address)
    {
        $this->authorize('update', $address);

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

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Alamat berhasil diperbarui');
    }

    /**
     * Hapus alamat
     */
    public function destroy(CustomerAddress $address)
    {
        $this->authorize('delete', $address);

        // Jika yang dihapus adalah default address
        if ($address->is_default) {
            $firstAddress = $address->customer->addresses()
                ->where('id', '!=', $address->id)
                ->first();
            
            if ($firstAddress) {
                $firstAddress->update(['is_default' => true]);
            }
        }

        $address->delete();

        return redirect()->route('customer.addresses.index')
            ->with('success', 'Alamat berhasil dihapus');
    }

    /**
     * Set alamat sebagai default (AJAX)
     */
    public function setDefault(CustomerAddress $address)
    {
        $this->authorize('update', $address);

        $address->customer->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return response()->json(['success' => true, 'message' => 'Alamat default berhasil diubah']);
    }
}
