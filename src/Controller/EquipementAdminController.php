<?php

namespace App\Controller;

use App\Entity\Equipement;
use App\Entity\Evenement;
use App\Form\Equipement1Type;
use App\Repository\EquipementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/equipement/admin')]
class EquipementAdminController extends AbstractController
{
    #[Route('/', name: 'app_equipement_admin_index', methods: ['GET'])]
    public function index(EquipementRepository $equipementRepository): Response
    {
        return $this->render('equipement_admin/index.html.twig', [
            'equipements' => $equipementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_equipement_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $equipement = new Equipement();
        $form = $this->createForm(Equipement1Type::class, $equipement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evenement = new Evenement();
            $evenement->setNom('evenement_' . $equipement->getNomEquipe());
            $evenement->setType('type_' . $equipement->getNomEquipe());
            $evenement->addEquipement($equipement);
            $equipement->addEvenement($evenement);

            $entityManager->persist($equipement);
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_equipement_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipement_admin/new.html.twig', [
            'equipement' => $equipement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipement_admin_show', methods: ['GET'])]
    public function show(Equipement $equipement): Response
    {
        return $this->render('equipement_admin/show.html.twig', [
            'equipement' => $equipement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_equipement_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Equipement $equipement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Equipement1Type::class, $equipement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_equipement_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipement_admin/edit.html.twig', [
            'equipement' => $equipement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipement_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Equipement $equipement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($equipement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_equipement_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
