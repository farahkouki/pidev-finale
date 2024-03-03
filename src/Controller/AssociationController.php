<?php
// src/Controller/AssociationController.php

namespace App\Controller;

use App\Entity\Association;
use App\Form\AssociationType;
use App\Form\AssociationSearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\QrCode;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Service\QrCodeGenerator;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\HeaderUtils;



#[Route('/association')]
class AssociationController extends AbstractController
{
    #[Route('/', name: 'app_association_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AssociationSearchType::class);
        $form->handleRequest($request);
    
        $associations = [];
    
        if ($form->isSubmitted() && $form->isValid()) {
            $searchTerm = $form->get('nom')->getData();
            $associations = $entityManager->getRepository(Association::class)->findByNom($searchTerm);
        } else {
            // Si le formulaire n'est pas soumis, affichez toutes les associations et triez-les par nom
            $associations = $entityManager->getRepository(Association::class)->findBy([], ['nom' => 'ASC']);
        }
    
        return $this->render('association/index.html.twig', [
            'associations' => $associations,
            'form' => $form->createView(),
        ]);
    }
    
    

    #[Route('/new', name: 'app_association_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $association = new Association();  // Ajoutez cette ligne pour créer une nouvelle instance d'Association

    $form = $this->createForm(AssociationType::class, $association);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($association);
        $entityManager->flush();

        return $this->redirectToRoute('app_association_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('association/new.html.twig', [
        'association' => $association,  // Assurez-vous de transmettre le modèle à la vue
        'form' => $form,
    ]);
}


#[Route('/{id}', name: 'app_association_show', methods: ['GET'])]
public function show(Request $request, Association $association, QrCodeGenerator $qrCodeGenerator): Response
{

    
        // Générer le code QR avec les informations du patient
        $qrCodeResult = $qrCodeGenerator->createQrCode($request, $association);


    return $this->render('association/show.html.twig', [
        'association' => $association,
        'qrCodeResult' => $qrCodeResult,
    ]);
}


#[Route('/{id}/edit', name: 'app_association_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Association $association, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(AssociationType::class, $association);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_association_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('association/edit.html.twig', [
        'association' => $association,
        'form' => $form,
    ]);
}



    #[Route('/{id}', name: 'app_association_delete', methods: ['POST'])]
    public function delete(Request $request, Association $association, EntityManagerInterface $entityManager): Response
    {
        // ... (restez inchangé)

        return $this->redirectToRoute('app_association_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}/pdf', name: 'app_association_pdf', methods: ['GET'])]
    public function generatePdf(Association $association)
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
    
        $html = $this->renderView('association/pdf.html.twig', [
            'association' => $association
        ]);
    
        $dompdf->loadHtml($html);
        $dompdf->render();
    
        $output = $dompdf->output();
        file_put_contents('association_'.$association->getId().'.pdf', $output);
    
        // Envoyer le PDF en téléchargement
        $response = new Response($output);
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'association_'.$association->getId().'.pdf'
        );
        $response->headers->set('Content-Disposition', $disposition);
    
        return $response;
    }


    
}
