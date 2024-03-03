<?php

namespace App\Controller;

use App\Entity\Voyage;
use App\Form\VoyageType;
use App\Repository\VoyageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\VoyageSearchType;

#[Route('/voyage')]
class VoyageController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'app_voyage_index', methods: ['GET', 'POST'])]
    public function index(Request $request, VoyageRepository $voyageRepository): Response
{
    $form = $this->createForm(VoyageSearchType::class);
    $form->handleRequest($request);

    $voyages = [];

    if ($form->isSubmitted() && $form->isValid()) {
        $searchTerm = $form->get('villeDepart')->getData();
        $voyages = $voyageRepository->findByVilleDepart($searchTerm);
    } else {
        $voyages = $voyageRepository->findAllSortedByVilleDepart();
    }

    return $this->render('voyage/index.html.twig', [
        'voyages' => $voyages,
        'form' => $form->createView(),
    ]);
}
    
    

    #[Route('/voyage/new', name: 'app_voyage_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $voyage = new Voyage();
        $form = $this->createForm(VoyageType::class, $voyage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Utilisation de l'entityManager
            $this->entityManager->persist($voyage);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_voyage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voyage/new.html.twig', [
            'voyage' => $voyage,
            'form' => $form,
        ]);
    }

    #[Route('/voyage/{id}/edit', name: 'app_voyage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Voyage $voyage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VoyageType::class, $voyage);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Pas besoin de convertir les activités en chaîne de caractères
    
            $entityManager->flush();
    
            return $this->redirectToRoute('app_voyage_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('voyage/edit.html.twig', [
            'voyage' => $voyage,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_voyage_delete', methods: ['POST'])]
    public function delete(Request $request, Voyage $voyage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voyage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($voyage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_voyage_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}', name: 'app_voyage_show', methods: ['GET'])]
    public function show(Voyage $voyage): Response
    {
        return $this->render('voyage/show.html.twig', [
            'voyage' => $voyage,
        ]);
    }

}
