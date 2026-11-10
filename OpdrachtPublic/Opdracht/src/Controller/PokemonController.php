<?php

namespace App\Controller;

use App\Entity\Pokemon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PokemonController extends AbstractController
{
    #[Route('/pokemon', name: 'app_pokemon')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $pokemons = $entityManager->getRepository(Pokemon::class)->findAll();
        return $this->render('pokemon/index.html.twig', [
            'pokemons' => $pokemons,
        ]);
    }
}
