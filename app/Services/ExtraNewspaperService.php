<?php

namespace App\Services;

use App\Repositories\Interfaces\ExtraNewspaperRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ExtraNewspaperService
{
    protected ExtraNewspaperRepositoryInterface $extraNewspaperRepository;

    public function __construct(ExtraNewspaperRepositoryInterface $extraNewspaperRepository)
    {
        $this->extraNewspaperRepository = $extraNewspaperRepository;
    }

    public function getPaginatedExtraNewspapers($search = null, $perPage = 15)
    {
        return $this->extraNewspaperRepository->searchAndPaginate($search, $perPage);
    }

    public function createExtraNewspaper(array $data)
    {
        try {
            return $this->extraNewspaperRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Error creating extra newspaper: '.$e->getMessage());
            throw $e;
        }
    }

    public function getExtraNewspaper($id)
    {
        return $this->extraNewspaperRepository->find($id);
    }

    public function updateExtraNewspaper($id, array $data)
    {
        try {
            return $this->extraNewspaperRepository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Error updating extra newspaper: '.$e->getMessage());
            throw $e;
        }
    }

    public function deleteExtraNewspaper($id)
    {
        try {
            return $this->extraNewspaperRepository->delete($id);
        } catch (\Exception $e) {
            Log::error('Error deleting extra newspaper: '.$e->getMessage());
            throw $e;
        }
    }
}
