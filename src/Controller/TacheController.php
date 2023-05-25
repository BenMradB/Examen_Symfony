<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Form\TacheType;
use App\Repository\TacheRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tache')]
class TacheController extends AbstractController
{
    #[Route('/', name: 'app_tache_index', methods: ['GET'])]
    public function index(TacheRepository $tacheRepository, Request $request): Response
    {
        $session = $request->getSession();

        if ($session->has("email")) {
            return $this->render('tache/index.html.twig', [
                'taches' => $tacheRepository->findAll(),
                'error' => 0
            ]);
        } else {

            return $this->render('tache/index.html.twig', [
                'taches' => [],
                'error' => -1
            ]);
        }
    }

    #[Route('/new', name: 'app_tache_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TacheRepository $tacheRepository): Response
    {
        $tache = new Tache();
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);
        $session = $request->getSession();

        if ($session->has('email')) {
            if ($form->isSubmitted() && $form->isValid()) {
                $tacheRepository->save($tache, true);

                return $this->redirectToRoute('app_tache_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('tache/new.html.twig', [
                'tache' => $tache,
                'form' => $form,
                'error' => 0
            ]);
        } else {
            return $this->renderForm('tache/new.html.twig', [
                'tache' => $tache,
                'form' => $form,
                'error' => 1
            ]);
        }
    }

    #[Route('/{id}', name: 'app_tache_show', methods: ['GET'])]
    public function show(Tache $tache): Response
    {
        return $this->render('tache/show.html.twig', [
            'tache' => $tache,
            'error' => 0
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tache_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tache $tache, TacheRepository $tacheRepository): Response
    {
        $session = $request->getSession();
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);
        if ($session->has('email')) {


            if ($form->isSubmitted() && $form->isValid()) {
                $tacheRepository->save($tache, true);

                return $this->redirectToRoute('app_tache_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('tache/edit.html.twig', [
                'tache' => $tache,
                'form' => $form,
                'error' => 0
            ]);
        } else {
            return $this->renderForm('tache/edit.html.twig', [
                'tache' => $tache,
                'form' => $form,
                'error' => 1
            ]);
        }
    }

    #[Route('/{id}', name: 'app_tache_delete', methods: ['POST'])]
    public function delete(Request $request, Tache $tache, TacheRepository $tacheRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tache->getId(), $request->request->get('_token'))) {
            $tacheRepository->remove($tache, true);
        }

        return $this->redirectToRoute('app_tache_index', [], Response::HTTP_SEE_OTHER);
    }
}
