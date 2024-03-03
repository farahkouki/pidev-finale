<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\Reservation1Type;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;



#[Route('/reservation/admin')]
class ReservationAdminController extends AbstractController
{
    #[Route('/', name: 'app_reservation_admin_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation_admin/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(Reservation1Type::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_admin/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_admin_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation_admin/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Reservation1Type::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_admin/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_admin_index', [], Response::HTTP_SEE_OTHER);
    }

    public function generateAllReservationsPdf(ReservationRepository $reservationRepository)
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
    
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Liste des réservations</title>
        </head>
        <body>
            <h1>Liste des réservations</h1>
            <table border="1">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Numéro de téléphone</th>
                    </tr>
                </thead>
                <tbody>';
        
        $reservations = $reservationRepository->findAll();
        foreach ($reservations as $reservation) {
            $html .= '
                    <tr>
                        <td>' . $reservation->getId() . '</td>
                        <td>' . $reservation->getNom() . '</td>
                        <td>' . $reservation->getPrenom() . '</td>
                        <td>' . $reservation->getEmail() . '</td>
                        <td>' . $reservation->getNumeroTelephone() . '</td>
                    </tr>';
        }
    
        $html .= '
                </tbody>
            </table>
        </body>
        </html>';
    
        $dompdf->loadHtml($html);
        $dompdf->render();
    
        $output = $dompdf->output();
        file_put_contents('all_reservations.pdf', $output);
    
        $response = new Response($output);
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'all_reservations.pdf'
        );
        $response->headers->set('Content-Disposition', $disposition);
    
        return $response;
    }
    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request)
    {
        $searchTerm = $request->query->get('q');

        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Reservation::class);
        $results = $repository->createQueryBuilder('s')
            ->where('s.nom LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();

        $formattedResults = [];
        foreach ($results as $reservation) {
            $formattedResults[] = [
                'id' => $reservation->getId(),
                'nom' => $reservation->getNom(),
                'prenom' => $reservation->getPrenom(),
                'email' => $reservation->getEmail(),
                'numeroTelephone' => $reservation->getNumeroTelephone(),
            ];
        }

        return new JsonResponse($formattedResults);
    }
}
