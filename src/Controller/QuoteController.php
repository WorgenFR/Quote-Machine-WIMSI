<?php

namespace App\Controller;

use App\Entity\Quote;
use App\Event\QuoteCreated;
use App\Repository\CategoryRepository;
use App\Repository\QuoteRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class QuoteController extends AbstractController
{
    private $quoteRepository;
    private $categorieRepository;
    private $security;

    public function __construct(QuoteRepository $quoteRepository, CategoryRepository $categorieRepository, \Symfony\Component\Security\Core\Security $security)
    {
        $this->quoteRepository = $quoteRepository;
        $this->categorieRepository = $categorieRepository;
        $this->security = $security;
    }

    #[Route('/quote', name: 'quote')]
    public function index(): Response
    {
        return $this->render('quote/index.html.twig', [
            'controller_name' => 'QuoteController',
        ]);
    }

    #[Route('/quotes', name: 'quote_index')]
    public function quoteIndex(PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $this->categorieRepository->findAll();
        $quotes = $this->quoteRepository->findAll();
        $pagination = $paginator->paginate($quotes, $request->query->getInt('page', 1), 10);

        return $this->render('quote/index.html.twig', [
            'quotes' => $pagination,
            'categories' => $categories,
        ]);
    }

    #[Route('/quotes/search', name: 'quote_search')]
    public function quoteSearch(Request $request): Response
    {
        $name = $request->query->get('name');
        $quotes = $this->quoteRepository->getQuotesByContent($name);
        $categories = $this->categorieRepository->findAll();
        $newQuotes = [];

        return $this->render('quote/index.html.twig', [
            'quotes' => $quotes,
            'categories' => $categories,
        ]);
    }

    #[Route('/quotes/new', name: 'quote_new')]
    #[Security("is_granted('ROLE_USER')")]
    public function quoteNew(Request $request): Response
    {
        $categories = $this->categorieRepository->findAll();

        return $this->render('quote/new.html.twig', ['case' => 'AJOUT', 'categories' => $categories], );
    }

    #[Route('/quotes/add', name: 'quote_add')]
    public function quoteAdd(Request $request, EventDispatcherInterface $eventDispatcher): Response
    {
        $user = $this->security->getUser();
        $em = $this->getDoctrine()->getManager();
        $content = $request->request->get('content');
        $meta = $request->request->get('meta');
        $catg_id = $request->request->get('catg');
        $catg = $this->categorieRepository->findOneBy(['id' => $catg_id]);
        //dd([$content, $meta]);

        $newQuote = new Quote();
        $newQuote->setContent($content);
        $newQuote->setMeta($meta);
        $newQuote->setCategory($catg);
        $newQuote->setAuthor($user);
        $em->persist($newQuote);
        $em->flush();

        $event = new QuoteCreated($newQuote);
        $eventDispatcher->dispatch($event);

        return $this->redirectToRoute('quote_index');
    }

    #[Route('/quotes/{id}/edit', name: 'quote_edit')]
    #[Security("is_granted('ROLE_USER')")]
    public function quoteEdit(Request $request, $id): Response
    {
        $categories = $this->categorieRepository->findAll();

        return $this->render('quote/new.html.twig', ['case' => 'EDIT', 'id' => $id, 'categories' => $categories]);
    }

    #[Route('/quotes/{id}/modify/', name: 'quote_modify')]
    public function quoteModify(Request $request, $id): Response
    {
        $categories = $this->categorieRepository->findAll();
        $quotes = $this->quoteRepository->findAll();
        $em = $this->getDoctrine()->getManager();
        $content = $request->request->get('content');
        $catgId = $request->request->get('catg');
        $catg = $this->categorieRepository->findOneBy(['id' => $catgId]);
        $meta = $request->request->get('meta');
        //dd([$content, $meta]);

        $quote = $this->quoteRepository->findOneBy(['id' => $id]);
        $quote->setContent($content);
        $quote->setMeta($meta);
        $quote->setCategory($catg);
        $em->persist($quote);
        $em->flush();

        return $this->redirectToRoute('quote_index');
    }

    #[Route('/quotes/{id}/delete', name: 'quote_delete')]
    #[Security("is_granted('ROLE_USER')")]
    public function quoteDelete(Request $request, $id): Response
    {
        $quote = $this->quoteRepository->findOneBy(['id' => $id]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($quote);
        $em->flush();

        return $this->redirectToRoute('quote_index');
    }

    #[Route('/quotes/csv', name: 'quote_csv')]
    public function quoteCSV(SerializerInterface $serializer): Response
    {
        $quote = new Quote();
        $quote->setContent('Acme Inc.');
        $quote->setMeta('Acme Inc.');
        $quotes = $this->quoteRepository->findAll();

        $json = $serializer->serialize($quotes, 'csv', ['groups' => 'group2']);

        return new Response($json, 200, ['Content-Type' => 'text/csv']);
    }
}
