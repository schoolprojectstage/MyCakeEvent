<?php

namespace App\Service;

use App\Repository\OrderRepository;

class OrderSearchService
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function orderSearch(mixed $search): mixed
    {
        $orders = [];
        if (!empty($search)) {
            $orders = $this->orderRepository->findLikeAll($search);
        }
        if (empty($search)) {
            // if search is empty, display everything
            $orders = $this->orderRepository->findAll();
        }
        return $orders;
    }
}
