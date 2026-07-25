<?php

namespace App\Services;

use App\Repositories\Interfaces\NewspaperRepositoryInterface;
use Illuminate\Support\Facades\Log;

class NewspaperService
{
    protected NewspaperRepositoryInterface $newspaperRepository;

    public function __construct(NewspaperRepositoryInterface $newspaperRepository)
    {
        $this->newspaperRepository = $newspaperRepository;
    }

    public function getPaginatedNewspapers($search = null, $perPage = 15)
    {
        return $this->newspaperRepository->searchAndPaginate($search, $perPage);
    }

    public function createNewspaper(array $data)
    {
        try {
            return $this->newspaperRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Error creating newspaper: '.$e->getMessage());
            throw $e;
        }
    }

    public function getNewspaper($id)
    {
        return $this->newspaperRepository->find($id);
    }

    public function updateNewspaper($id, array $data)
    {
        try {
            return $this->newspaperRepository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Error updating newspaper: '.$e->getMessage());
            throw $e;
        }
    }

    public function deleteNewspaper($id)
    {
        try {
            return $this->newspaperRepository->delete($id);
        } catch (\Exception $e) {
            Log::error('Error deleting newspaper: '.$e->getMessage());
            throw $e;
        }
    }
}
