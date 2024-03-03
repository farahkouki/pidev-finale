<?php

namespace App\Controller;

use App\Entity\Association;
use App\Form\Association1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/association/admin')]
class AssociationAdminController extends AbstractController
{
    #[Route('/', name: 'app_association_admin_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $associations = $entityManager
            ->getRepository(Association::class)
            ->findAll();

        return $this->render('association_admin/index.html.twig', [
            'associations' => $associations,
        ]);
    }

    #[Route('/new', name: 'app_association_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $association = new Association();
        $form = $this->createForm(Association1Type::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($association);
            $entityManager->flush();

            return $this->redirectToRoute('app_association_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('association_admin/new.html.twig', [
            'association' => $association,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_association_admin_show', methods: ['GET'])]
    public function show(Association $association): Response
    {
        return $this->render('association_admin/show.html.twig', [
            'association' => $association,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_association_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Association $association, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Association1Type::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_association_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('association_admin/edit.html.twig', [
            'association' => $association,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_association_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Association $association, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$association->getId(), $request->request->get('_token'))) {
            $entityManager->remove($association);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_association_admin_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request)
    {
        $searchTerm = $request->query->get('q');

        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Association::class);
        $results = $repository->createQueryBuilder('s')
            ->where('s.nom LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();

        $formattedResults = [];
        foreach ($results as $association) {
            $formattedResults[] = [
                'id' => $association->getId(),
                'nom' => $association->getNom(),
                'description' => $association->getDescription(),
                'adresse' => $association->getAdresse(),
                'email' => $association->getEmail(),
                'telephone' => $association->getNumeroTelephone(),
                'dateCreation' => $association->getDateCreation() ? $association->getDateCreation()->format('Y-m-d H:i:s') : '',
            ];
        }

        return new JsonResponse($formattedResults);
    }
}
