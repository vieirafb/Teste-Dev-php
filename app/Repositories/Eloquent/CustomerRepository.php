<?php

namespace App\Repositories\Eloquent;

use App\Contracts\CustomerRepositoryInterface;
use App\Models\Address;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function create(array $data): array
    {
        DB::beginTransaction();

        $address = Address::create($data['address']);

        $data['address_id'] = $address->id;

        $customer = Customer::create($data);

        DB::commit();

        $response = $customer->toArray();
        $response['address'] = $address->toArray();
        unset($response['address_id']);

        return $response;
    }

    public function edit(int $id, array $data): array
    {
        DB::beginTransaction();

        $customer = Customer::findOrFail($id);

        $customer->fill($data)->save();
        $customer->address->fill($data['address'])->save();

        DB::commit();

        $response = $customer->toArray();
        unset($response['address_id']);

        return $response;
    }

    public function get(int $id): array
    {
        $costumer = Customer::find($id);
        return $costumer->toArray();
    }

    public function delete(int $id): void
    {
        $customer = Customer::find($id);

        if (!$customer) return;

        DB::beginTransaction();

        $customer->delete();
        $customer->address->delete();

        DB::commit();
    }
}
