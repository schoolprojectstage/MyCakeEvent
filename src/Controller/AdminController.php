<?php

namespace App\Controller;

use App\Form\OrderFormType;
use App\Form\SearchCakeFormType;
use App\Form\SearchUserFormType;
use App\Repository\CakeRepository;
use App\Repository\DepartmentRepository;
use App\Repository\UserRepository;
use App\Service\CakeSearchService;
use App\Service\OrderSearchService;
use App\Service\UserSearchService;
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
    public function cakeIndex(
        Request $request,
        CakeSearchService $cakeSearchService,
        DepartmentRepository $departmentRepository
    ): Response {
        // fetching all departments for the scrolling menu
        $departmentsDisplay = $departmentRepository->findAll();
        // creating form
        $searchForm = $this->createForm(SearchCakeFormType::class);
        $searchForm->handleRequest($request);
        // initializing search and department variables before the form is set
        $search = "";
        $department = "";

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchRequest = $request->get('search_cake_form');

            // some bricolage to please phpcs
            if (is_array($searchRequest)) {
                $search = $searchRequest['search'];

                // for homepage buttons (which don't take departments into account)
                if (isset($searchRequest['department'])) {
                    $department = $searchRequest['department'];
                }
            }
        }
        // calling the CakeSearchService

        $cakes = $cakeSearchService->cakeSearch($search, $department);

        return $this->renderForm('admin/cakeslist.html.twig', [
            'cakes' => $cakes,
            'searchForm' => $searchForm,
            'search' => $search,
            'departments' => $departmentsDisplay,
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
    public function allCustomer(UserSearchService $searchService, Request $request): Response
    {
        $searchForm = $this->createForm(SearchUserFormType::class);
        $searchForm->handleRequest($request);
        $search = "";

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchRequest = $request->get('search_user_form');

            if (is_array($searchRequest)) {
                $search = $searchRequest['search'];
            }
        }

        $users = $searchService->userSearch($search);

        return $this->renderForm('admin/customer.html.twig', [
            'users' => $users,
            'searchForm' => $searchForm,
            'search' => $search,]);
    }
}
