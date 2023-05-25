<?php

namespace App\Controller;

use App\Entity\Utulisateur;
use App\Form\UtulisateurType;
use App\Repository\UtulisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('')]
class UtulisateurController extends AbstractController
{
    #[Route('/', name: 'app_utulisateur_index', methods: ['GET'])]
    public function index(UtulisateurRepository $utulisateurRepository): Response
    {
        return $this->render('utulisateur/index.html.twig', [
            'utulisateurs' => $utulisateurRepository->findAll(),
            'error' => ''
        ]);
    }

    #[Route('/login', name: 'app_utulisateur_new', methods: ['GET', 'POST'])]
    public function login(Request $request, UtulisateurRepository $utulisateurRepository): Response
    {

        $utulisateur = new Utulisateur();
        $form = $this->createForm(UtulisateurType::class, $utulisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $exists = $utulisateurRepository->findOneBy(['adresse_email' => $utulisateur->getAdresseEmail()]);

            if ($exists == null) {
                return $this->render('utulisateur/new.html.twig', [
                    'utulisateur' => $utulisateur,
                    'form' => $form,
                    'error'=> 'Wrong Email Or Password '
                ]);
            } else {
                $session = $request->getSession();
                $session->set('email', $utulisateur->getAdresseEmail());
                return $this->redirectToRoute('app_tache_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('utulisateur/new.html.twig', [
            'utulisateur' => $utulisateur,
            'form' => $form,
            'error' => ''
        ]);
    }

    #[Route('/{id}', name: 'app_utulisateur_show', methods: ['GET'])]
    public function show(Utulisateur $utulisateur): Response
    {
        return $this->render('utulisateur/show.html.twig', [
            'utulisateur' => $utulisateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utulisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utulisateur $utulisateur, UtulisateurRepository $utulisateurRepository): Response
    {
        $form = $this->createForm(UtulisateurType::class, $utulisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utulisateurRepository->save($utulisateur, true);

            return $this->redirectToRoute('app_utulisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utulisateur/edit.html.twig', [
            'utulisateur' => $utulisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utulisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utulisateur $utulisateur, UtulisateurRepository $utulisateurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utulisateur->getId(), $request->request->get('_token'))) {
            $utulisateurRepository->remove($utulisateur, true);
        }

        return $this->redirectToRoute('app_utulisateur_index', [], Response::HTTP_SEE_OTHER);
    }
}
