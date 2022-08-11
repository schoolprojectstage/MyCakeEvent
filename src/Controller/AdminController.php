<?php

namespace App\Controller;

use App\Form\OrderFormType;
use App\Repository\CakeRepository;
use App\Repository\UserRepository;
use App\Service\OrderSearchService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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

    #[Route('/gateaux', name: 'cakes', methods: ['POST', 'GET'])]
    public function cakesIndex(CakeRepository $cakeRepository): Response
    {
        $cakes = $cakeRepository->findAll();
        return $this->render('admin/cakeslist.html.twig', [
            'cakes' => $cakes,
        ]);
    }

    #[Route('/commandes', name: 'orders')]
    public function ordersIndex(
        Request $request,
        OrderSearchService $orderSearchService
    ): Response {

        $searchForm = $this->createForm(OrderFormType::class);
        $searchForm->handleRequest($request);
        $search = "";

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchRequest = $request->get('order_form');

            if (is_array($searchRequest)) {
                $search = $searchRequest['search'];
            }
        }

        $orders = $orderSearchService->orderSearch($search);

        return $this->renderForm('admin/orderslist.html.twig', [
            'orders' => $orders,
            'searchForm' => $searchForm,
            'search' => $search,
        ]);
    }

    #[Route('/customers', name: 'customers')]
    public function allCustomer(UserRepository $userRepository): Response
    {
        $roles = 'ROLE_CUSTOMER';
        $users = $userRepository->findByRoles($roles);

        return $this->render('admin/customer.html.twig', [
            'users' => $users]);
    }
}
