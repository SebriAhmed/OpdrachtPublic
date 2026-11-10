<?php

namespace App\Tests\Controller;

use App\Entity\Pokemon;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PokemonControllerTest extends WebTestCase
{
    public function testCompletePokemonCardFlow(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get(EntityManagerInterface::class);
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);

        $pikachu = (new Pokemon())
            ->setName('Pikachu')
            ->setCardValue('400.00')
            ->setImage('pikachu.jpg')
            ->setDescription('A Pikachu card');
        $entityManager->persist($pikachu);
        $entityManager->flush();

        $crawler = $client->request('GET', '/pokemon');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Pokemon kaarten');

        $client->click($crawler->selectLink('Meer info')->link());
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Pikachu');
        self::assertSelectorTextContains('.card-body', 'A Pikachu card');

        $crawler = $client->request('GET', '/pokemon/new');
        self::assertResponseIsSuccessful();
        $client->submit($crawler->selectButton('Toevoegen')->form(), [
            'pokemon[name]' => 'Eevee',
            'pokemon[cardValue]' => '99.50',
            'pokemon[image]' => 'mew.jpg',
            'pokemon[description]' => 'Een testkaart',
        ]);
        self::assertResponseRedirects('/pokemon');
        $client->followRedirect();
        self::assertSelectorTextContains('.alert-success', 'toegevoegd');

        $eevee = $entityManager->getRepository(Pokemon::class)->findOneBy(['name' => 'Eevee']);
        self::assertNotNull($eevee);

        $crawler = $client->request('GET', '/pokemon/'.$eevee->getId().'/edit');
        self::assertResponseIsSuccessful();
        $client->submit($crawler->selectButton('Bijwerken')->form(), [
            'pokemon[name]' => 'Eevee VMAX',
            'pokemon[cardValue]' => '125.00',
            'pokemon[image]' => 'mew.jpg',
            'pokemon[description]' => 'Een bijgewerkte testkaart',
        ]);
        self::assertResponseRedirects('/pokemon');
        $entityManager->refresh($eevee);
        self::assertSame('Eevee VMAX', $eevee->getName());

        $crawler = $client->request('GET', '/pokemon/delete-confirm/'.$eevee->getId());
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Eevee VMAX wilt verwijderen');
        $client->submit($crawler->selectButton('Ja, kaart verwijderen')->form());
        self::assertResponseRedirects('/pokemon');

        $entityManager->clear();
        self::assertNull($entityManager->getRepository(Pokemon::class)->find($eevee->getId()));
    }
}
