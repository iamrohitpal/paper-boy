<?php

namespace App\Services;

use App\Repositories\Interfaces\CustomerRepositoryInterface;
use Illuminate\Support\Facades\Log;

class CustomerService
{
    protected CustomerRepositoryInterface $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function getPaginatedCustomers($search = null, $perPage = 15)
    {
        return $this->customerRepository->searchAndPaginate($search, $perPage);
    }

    public function createCustomer(array $data)
    {
        try {
            // Generate Customer ID if not provided
            if (empty($data['customer_id'])) {
                $data['customer_id'] = 'CUST-'.strtoupper(uniqid());
            }

            return $this->customerRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Error creating customer: '.$e->getMessage());
            throw $e;
        }
    }

    public function getCustomer($id)
    {
        return $this->customerRepository->find($id);
    }

    public function updateCustomer($id, array $data)
    {
        try {
            return $this->customerRepository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Error updating customer: '.$e->getMessage());
            throw $e;
        }
    }

    public function deleteCustomer($id)
    {
        try {
            return $this->customerRepository->delete($id);
        } catch (\Exception $e) {
            Log::error('Error deleting customer: '.$e->getMessage());
            throw $e;
        }
    }
}
