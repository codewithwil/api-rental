<?php

namespace App\Services\Report\Kas;

use App\{
    Repositories\Report\Kas\KasRepositoryInterface,
    Traits\ApiResponse
};

class KasService
{
    use ApiResponse;

    public function __construct(
        protected KasRepositoryInterface $kasService
    ) {}

    public function index()
    {
        return $this->successResponse([
            'kas' => $this->kasService->getAll()
        ]);
    }

    public function show($id)
    {
        return $this->successResponse([
            'kas' => $this->kasService->find($id)
        ]);
    }

    public function getTotalMasuk()
    {
        return $this->successResponse([
            'kas' => $this->kasService->getTotalMasuk()
        ]);
    }
    
    public function getTotalKeluar()
    {
        return $this->successResponse([
            'kas' => $this->kasService->getTotalKeluar()
        ]);
    }

    public function getSaldo()
    {
        return $this->successResponse([
            'kas' => $this->kasService->getSaldo()
        ]);
    }
}
