<?php

namespace App\Tests\Command;

use App\Repository\CategoryRepository;
use App\Repository\QuoteRepository;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class AppRandomQuoteCommandTest extends KernelTestCase
{
    /*private $quoteRepository;
    private $categoryRepository;

    public function __construct(QuoteRepository $quoteRepository, CategoryRepository $categoryRepository)
    {
        $this->quoteRepository = $quoteRepository;
        $this->categoryRepository = $categoryRepository;

        parent::__construct();
    }*/

    public function testExecute(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        // préfixez la clé avec deux tirets lors du passage des options, ex: '--some-option' => 'option_value',
        $commandTester->execute(['command' => $command->getName(), 'category' => 1]);

        // Sortie de la commande dans la console
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Voici une citation aléatoire', $output);
    }
}
