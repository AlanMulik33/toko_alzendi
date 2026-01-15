<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\CustomerAddress;

class CustomerAddressPolicy
{
    /**
     * Determine if the user can view the model.
     */
    public function view(Customer $customer, CustomerAddress $address): bool
    {
        return $customer->id === $address->customer_id;
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(Customer $customer, CustomerAddress $address): bool
    {
        return $customer->id === $address->customer_id;
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(Customer $customer, CustomerAddress $address): bool
    {
        return $customer->id === $address->customer_id;
    }
}
