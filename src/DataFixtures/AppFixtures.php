<?php

namespace App\DataFixtures;

use App\Entity\Artiste;
use App\Entity\Genre;
use App\Entity\Album;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = Yaml::parseFile(__DIR__ . '/../../data/extrait.yml');

        foreach ($data as $albumData) {
            $album = new Album();
            $album->setTitle($albumData['title']);
            $album->setYear($albumData['releaseYear']);

            $artiste = $manager->getRepository(Artiste::class)->findOneBy(['name' => $albumData['by']]);
            if (!$artiste) {
                $artiste = new Artiste();
                $artiste->setName($albumData['by']);
                $manager->persist($artiste);
            }
            $album->addArtiste($artiste);

            foreach ($albumData['genre'] as $genreName) {
                $genre = $manager->getRepository(Genre::class)->findOneBy(['name' => $genreName]);
                if (!$genre) {
                    $genre = new Genre();
                    $genre->setName($genreName);
                    $manager->persist($genre);
                }
                $album->addGenre($genre);
            }

            $manager->persist($album);
        }

        $manager->flush();
    }
}
