<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\Artiste;
use App\Repository\ArtisteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class ArtistController extends AbstractController
{
    /**
     * @Route("/artists", name="artists")
     */
    public function index(Request $request, ArtisteRepository $artistRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 20; // Nombre d'artistes par page
        $artists = $artistRepository->findPaginatedArtists($page, $limit);


        return $this->render('artist/index.html.twig', [
            'artists' => $artists, // les artistes récupérés
        ]);
    }

    /**
     * @Route("/artist/{id}/delete", name="artist_delete", methods={"POST"})
     */
    public function delete(Request $request, Artiste $artist,  EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $artist->getId(), $request->request->get('_token'))) {
            $entityManager->remove($artist);
            $entityManager->flush();
        }

        return $this->redirectToRoute('artists');
    }
}
