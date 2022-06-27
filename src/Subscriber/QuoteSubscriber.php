<?php

namespace App\Subscriber;

use App\Event\QuoteCreated;
use App\Repository\QuoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class QuoteSubscriber implements EventSubscriberInterface
{
    private $em;
    private $security;
    private $quoteRepository;

    public function __construct(EntityManagerInterface $em, Security $security, QuoteRepository $quoteRepository)
    {
        $this->em = $em;
        $this->security = $security;
        $this->quoteRepository = $quoteRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [QuoteCreated::class => 'onQuoteCreated'];
    }

    public function onQuoteCreated(QuoteCreated $event): void
    {
        $user = $this->security->getUser();
        $category = $event->getQuote()->getCategory();
        $quoteAuthor = $event->getQuote()->getAuthor();
        $experience = $quoteAuthor->getExperience();

        $quotes = $this->quoteRepository->findBy(['category' => $category, 'author' => $user]);

        if (0 !== count($quotes)) {
            $quoteAuthor?->setExperience($experience + 100);
        } else {
            $quoteAuthor?->setExperience($experience + 120);
        }

        $this->em->flush();
    }
}
