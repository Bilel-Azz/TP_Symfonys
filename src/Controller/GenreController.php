<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\Genre;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class GenreController extends AbstractController
{
    /**
     * @Route("/genres", name="genres")
     */
    public function index(Request $request, GenreRepository $genreRepository): Response
    {
        
        $page = $request->query->getInt('page', 1);
        $limit = 10; // Nombre de genres par page
        $genres = $genreRepository->findPaginatedGenres($page, $limit);


        return $this->render('genre/index.html.twig', [
            'genres' => [], // Remplacez ceci par les genres récupérés
        ]);
    }

    /**
     * @Route("/genre/{id}/delete", name="genre_delete", methods={"POST"})
     */
    public function delete(Request $request, Genre $genre,  EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $genre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($genre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('genres');
    }
}

