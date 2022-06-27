<?php

namespace App\Tests;

use App\Factory\PostFactory;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;

class QuoteTest extends WebTestCase
{
    use Factories;

    //Vérification du fonctionnement d la page d'accueil
    public function testHome(): void
    {
        $client = static::createClient();
        $client->request('GET', '/quotes');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('#titre', 'Bienvenue sur QuoteMachine !');
    }

    public function testLogin(): void
    {
        $client = static::createClient();
        $post = PostFactory::createOne();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['name' => 'Antoine Grappin']);
        $client->loginUser($testUser);

        $client->request('GET', '/profile');
        $this->assertResponseIsSuccessful();
        $post->assertPersisted();
        $this->assertSelectorTextContains('#hello-message', 'Hello Antoine Grappin !');
    }

    public function testAddQuote(): void
    {
        $client = static::createClient();
        $client->request('GET', '/quotes/new');
        $this->assertResponseIsSuccessful();

        $formData = [
            'content' => 'Contenu Test',
            'meta' => 'Meta Test',
            'catg' => 1,
        ];

        $client->submitForm('submit-quote', $formData);
        $client->followRedirects();
    }

    public function testAddCategory()
    {
        $client = static::createClient();
        $client->request('GET', '/categorie/new');
        $this->assertResponseIsSuccessful();

        $client->submitForm('submit-category', ['name' => 'Category Test']);
    }
}
