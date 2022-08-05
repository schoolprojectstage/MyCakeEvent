<?php

namespace App\Controller;

use App\Form\SearchOrderFormType;
use App\Repository\CakeRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/espace-admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/gateaux', name: 'cakes')]
    public function cakesIndex(CakeRepository $cakeRepository): Response
    {
        $cakes = $cakeRepository->findAll();
        return $this->render('admin/cakeslist.html.twig', [
            'cakes' => $cakes,
        ]);
    }

    #[Route('/commandes', name: 'orders')]
    public function ordersIndex(OrderRepository $orderRepository, Request $request): Response
    {
        $orders = $orderRepository->findAll();

        $searchForm = $this->createForm(SearchOrderFormType::class);
        $searchForm->handleRequest($request);
        return $this->render('admin/orderslist.html.twig', [
            'orders' => $orders,
        ]);
    }
}
