# Eindtoets Symfony

## LEZEN!
Lees de opdracht goed door, lees alles maak aantekeningen waar nodig! 

Links die je kunt gebruiken:
- https://eduinsights.nl/docs/symfony-7-4
- https://twig.symfony.com/doc/3.x/
- https://symfony.com/doc/7.4
- https://getbootstrap.com/

## Functies

- Overzicht van alle Pokemon kaarten
- Details van een kaart bekijken
- Kaarten toevoegen en wijzigen
- Kaarten verwijderen na een bevestiging
- Meegeleverde database-export met voorbeelddata

## Installatie

1. Clone de repository en open de map `OpdrachtPublic/Opdracht`.
2. Voer `composer install` uit.
3. Importeer `backup/pokemon_shop.sql` in PHPMyAdmin. De database `pokemon_shop` wordt daarbij aangemaakt.
4. Pas zo nodig `DATABASE_URL` aan in een nieuw bestand `.env.local`. De standaardconfiguratie gebruikt MySQL/MariaDB met gebruiker `root` en een leeg wachtwoord.
5. Start de applicatie met `symfony serve`.

De database-export bevat de tabellen, migratiegegevens en drie voorbeeldkaarten. `.env.local` wordt niet door Git bijgehouden, zodat lokale wachtwoorden niet worden gepusht.

## Testen

Voer `php bin/phpunit` uit. De testomgeving gebruikt een lokale SQLite-database en staat los van de ontwikkel-database.

### Happy Hunting!
