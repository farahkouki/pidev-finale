<?php

namespace App\Controller;

use App\Entity\Sponsor;
use App\Form\Sponsor1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/sponsor/admin')]
class SponsorAdminController extends AbstractController
{
    #[Route('/', name: 'app_sponsor_admin_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $sponsors = $entityManager
            ->getRepository(Sponsor::class)
            ->findAll();

        return $this->render('sponsor_admin/index.html.twig', [
            'sponsors' => $sponsors,
        ]);
    }

    #[Route('/new', name: 'app_sponsor_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sponsor = new Sponsor();
        $form = $this->createForm(Sponsor1Type::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sponsor);
            $entityManager->flush();

            return $this->redirectToRoute('app_sponsor_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sponsor_admin/new.html.twig', [
            'sponsor' => $sponsor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sponsor_admin_show', methods: ['GET'])]
    public function show(Sponsor $sponsor): Response
    {
        return $this->render('sponsor_admin/show.html.twig', [
            'sponsor' => $sponsor,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sponsor_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sponsor $sponsor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Sponsor1Type::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sponsor_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sponsor_admin/edit.html.twig', [
            'sponsor' => $sponsor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sponsor_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Sponsor $sponsor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sponsor->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sponsor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sponsor_admin_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request)
    {
        $searchTerm = $request->query->get('q');

        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Sponsor::class);
        $results = $repository->createQueryBuilder('s')
            ->where('s.nom LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();

        $formattedResults = [];
        foreach ($results as $sponsor) {
            $formattedResults[] = [
                'id' => $sponsor->getId(),
                'nom' => $sponsor->getNom(),
                'adresse' => $sponsor->getAdresse(),
                'mail' => $sponsor->getMail(),
                'telephone' => $sponsor->getTelephone(),
                'dateCreation' => $sponsor->getDateCreation() ? $sponsor->getDateCreation()->format('Y-m-d H:i:s') : '',
            ];
        }

        return new JsonResponse($formattedResults);
    }


}
