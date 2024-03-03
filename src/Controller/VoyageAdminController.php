<?php

namespace App\Controller;

use App\Entity\Voyage;
use App\Form\Voyage1Type;
use App\Repository\VoyageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\HeaderUtils;

#[Route('/voyage/admin')]
class VoyageAdminController extends AbstractController
{
    #[Route('/', name: 'app_voyage_admin_index', methods: ['GET'])]
    public function index(VoyageRepository $voyageRepository): Response
    {
        return $this->render('voyage_admin/index.html.twig', [
            'voyages' => $voyageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_voyage_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $voyage = new Voyage();
        $form = $this->createForm(Voyage1Type::class, $voyage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($voyage);
            $entityManager->flush();

            return $this->redirectToRoute('app_voyage_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voyage_admin/new.html.twig', [
            'voyage' => $voyage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_voyage_admin_show', methods: ['GET'])]
    public function show(Voyage $voyage): Response
    {
        return $this->render('voyage_admin/show.html.twig', [
            'voyage' => $voyage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_voyage_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Voyage $voyage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Voyage1Type::class, $voyage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_voyage_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voyage_admin/edit.html.twig', [
            'voyage' => $voyage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_voyage_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Voyage $voyage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voyage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($voyage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_voyage_admin_index', [], Response::HTTP_SEE_OTHER);
    }




    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request)
    {
        $searchTerm = $request->query->get('q');

        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Voyage::class);

        // Si aucun terme de recherche n'est spécifié, renvoyer tous les voyages
        if (empty($searchTerm)) {
            $results = $repository->findAll();
        } else {
            $results = $repository->createQueryBuilder('s')
                ->where('s.nom LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%')
                ->getQuery()
                ->getResult();
        }

        $formattedResults = [];
        foreach ($results as $voyage) {
            $formattedResults[] = [
                'id' => $voyage->getId(),
                'villeDepart' => $voyage->getVilleDepart(),
                'destination' => $voyage->getDestination(),
                'heureDepart' => $voyage->getHeureDepart(),
                'heureArrivee' => $voyage->getHeureArrivee(),
                'typeVoyage' => $voyage->getTypeVoyage(),
                'type' => $voyage->getType(),
                'activites' => $voyage->getActivites(),
            ];
        }

        return new JsonResponse($formattedResults);
    }
    
  
}
