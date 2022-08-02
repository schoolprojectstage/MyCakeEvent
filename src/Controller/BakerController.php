<?php

namespace App\Controller;

use App\Entity\Baker;
use App\Entity\User;
use App\Form\BakerType;
use App\Form\BakerModifyType;
use App\Repository\BakerRepository;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

#[Route('/patissier', name: 'app_baker_')]
class BakerController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/', name: 'index')]
    public function index(BakerRepository $bakerRepository): Response
    {
        $bakers = $bakerRepository->findAll();
        return $this->render('baker/index.html.twig', [
            'bakers' => $bakers,
        ]);
    }

    #[Route('/nouveau', name: 'form')]
    public function newBaker(Request $request, BakerRepository $bakerRepository): Response
    {
        $baker = new Baker();
        $form = $this->createForm(BakerType::class, $baker);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $bakerRepository->add($baker, true);
            return $this->redirectToRoute('app_baker_index');
        }

        return $this->renderForm('baker/new.html.twig', [
            'form' => $form, 'baker' => $baker,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Baker $baker, BakerRepository $bakerRepository): Response
    {
        if (is_string($request->request->get('token')) || is_null($request->request->get('token'))) {
            if ($this->isCsrfTokenValid('delete' . $baker->getId(), $request->request->get('token'))) {
                $bakerRepository->remove($baker, true);
            } else {
                throw new Exception(message: 'Impossible de supprimer le patissier.');
            }
        }

        return $this->redirectToRoute('app_baker_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'list')]
    public function detail(Baker $baker): Response
    {
        return $this->render('baker/show.html.twig', [
            'baker' => $baker,
        ]);
    }

    #[Route('/{id}/modifier', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Baker $baker, BakerRepository $bakerRepository): Response
    {
        // initializing modifyForm in case conditions are not met
        $modifyForm = "";

        //make sure only the current baker and the admin can access this route
        /** @var User $user */
        $user = $this->getUser();

        if (
            (in_array('ROLE_BAKER', $user->getRoles()) && $user->getBaker())
            || in_array('ROLE_ADMIN', $user->getRoles())
        ) {
            $modifyForm = $this->createForm(BakerModifyType::class, $baker);
            $modifyForm->handleRequest($request);

            if ($modifyForm->isSubmitted() && $modifyForm->isValid()) {
                $baker->setUpdateAt(new DateTime());
                $bakerRepository->add($baker, true);

                if (in_array('ROLE_BAKER', $user->getRoles())) {
                    return $this->redirectToRoute(
                        'app_bakerspace_show',
                        ['id' => $user->getId()]
                    );
                } else {
                    return $this->redirectToRoute('app_baker_index', []);
                }
            }
        }

        return $this->renderForm('baker/edit.html.twig', [
            'baker' => $baker,
            'modifyForm' => $modifyForm,
        ]);
    }
}
