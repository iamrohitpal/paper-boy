<?php

namespace App\Services;

use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    protected SubscriptionRepositoryInterface $subscriptionRepository;

    public function __construct(SubscriptionRepositoryInterface $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function getPaginatedSubscriptions($search = null, $perPage = 15)
    {
        return $this->subscriptionRepository->searchAndPaginate($search, $perPage);
    }

    public function createSubscription(array $data)
    {
        try {
            if ($data['delivery_days'] !== 'Custom') {
                $data['custom_days'] = null;
            } elseif (isset($data['custom_days'])) {
                $data['custom_days'] = json_encode($data['custom_days']);
            }

            return $this->subscriptionRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Error creating subscription: '.$e->getMessage());
            throw $e;
        }
    }

    public function getSubscription($id)
    {
        return $this->subscriptionRepository->find($id);
    }

    public function updateSubscription($id, array $data)
    {
        try {
            if ($data['delivery_days'] !== 'Custom') {
                $data['custom_days'] = null;
            } elseif (isset($data['custom_days'])) {
                $data['custom_days'] = json_encode($data['custom_days']);
            }

            return $this->subscriptionRepository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Error updating subscription: '.$e->getMessage());
            throw $e;
        }
    }

    public function deleteSubscription($id)
    {
        try {
            return $this->subscriptionRepository->delete($id);
        } catch (\Exception $e) {
            Log::error('Error deleting subscription: '.$e->getMessage());
            throw $e;
        }
    }
}
