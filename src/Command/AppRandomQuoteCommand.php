<?php

namespace App\Command;

use App\Repository\CategoryRepository;
use App\Repository\QuoteRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:random-quote',
    description: 'Return a random quote with optional category',
)]
class AppRandomQuoteCommand extends Command
{
    private $quoteRepository;
    private $categoryRepository;

    public function __construct(QuoteRepository $quoteRepository, CategoryRepository $categoryRepository)
    {
        $this->quoteRepository = $quoteRepository;
        $this->categoryRepository = $categoryRepository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('category', InputArgument::OPTIONAL, 'Category selected');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $category = $input->getArgument('category');

        //Vérification sur la présence de citations
        if (0 === count($this->quoteRepository->findAll())) {
            $io->error('empty database');
        }

        if ($category) {
            $categoryselected = $this->categoryRepository->findOneBy(['id' => $category]);
            $randomQuote = $this->quoteRepository->findOneBy(['category' => $categoryselected]);
        } else {
            $length = count($this->quoteRepository->findAll());
            $randomQuote = $this->quoteRepository->findOneBy(['id' => random_int(1, $length)]);
        }

        $io->title('Voici une citation aléatoire :');
        if ($randomQuote->getContent()) {
            $io->section($randomQuote->getContent());
        }
        if ($randomQuote->getMeta()) {
            $io->section($randomQuote->getMeta());
        }

        return Command::SUCCESS;
    }
}
