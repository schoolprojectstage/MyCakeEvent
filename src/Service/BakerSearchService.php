<?php

namespace App\Service;

use App\Repository\BakerRepository;

class BakerSearchService
{
    private BakerRepository $bakerRepository;

    public function __construct(BakerRepository $bakerRepository)
    {
        $this->bakerRepository = $bakerRepository;
    }

    public function bakerSearch(mixed $search): mixed
    {
        $orders = [];
        if (!empty($search)) {
            $orders = $this->bakerRepository->findLikeAll($search);
        }
        if (empty($search)) {
            // if search is empty, display everything
            $orders = $this->bakerRepository->findAll();
        }
        return $orders;
    }
}
