<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Form\PokemonType;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/pokemon/new', name: 'app_pokemon_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pokemon = new Pokemon();
        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pokemon);
            $entityManager->flush();

            $this->addFlash('success', 'De Pokemon kaart is toegevoegd.');

            return $this->redirectToRoute('app_pokemon');
        }

        return $this->render('pokemon/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/pokemon/{id}', name: 'app_pokemon_show', requirements: ['id' => '\\d+'], methods: ['GET'])]
    public function show(int $id, PokemonRepository $pokemonRepository): Response
    {
        $pokemon = $pokemonRepository->find($id);

        if ($pokemon === null) {
            throw $this->createNotFoundException('Deze Pokemon kaart bestaat niet.');
        }

        return $this->render('pokemon/show.html.twig', [
            'pokemon' => $pokemon,
        ]);
    }
}
