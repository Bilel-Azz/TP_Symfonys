<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Doctrine\ORM\Entity;
use App\Entity\Album;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AlbumController extends AbstractController
{
    /**
     * @Route("/albums", name="albums")
     */


    public function index(Request $request, AlbumRepository $albumRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 20; // Nombre d'albums par page
        $albums = $albumRepository->findPaginatedAlbums($page, $limit); 

        return $this->render('album/index.html.twig', [
            'albums' => $albums,
        ]);
    }

    /**
     * @Route("/album/{id}/delete", name="album_delete", methods={"POST"})
     */
    public function delete(Request $request, Album $album,  EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $album->getId(), $request->request->get('_token'))) {
            $entityManager->remove($album);
            $entityManager->flush();
        }

        return $this->redirectToRoute('albums');
    }



    
}

