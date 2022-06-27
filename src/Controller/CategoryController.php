<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\QuoteRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    private $quoteRepository;
    private $categorieRepository;

    public function __construct(QuoteRepository $quoteRepository, CategoryRepository $categorieRepository)
    {
        $this->quoteRepository = $quoteRepository;
        $this->categorieRepository = $categorieRepository;
    }

    #[Route('/quotes/category/{id}', name: 'quote_catg')]
    public function quoteCategorie(PaginatorInterface $paginator, $id, Request $request): Response
    {
        $categories = $this->categorieRepository->findAll();
        $categorie = $this->categorieRepository->findOneBy(['id' => $id]);
        $quotes = $this->quoteRepository->findBy(['category' => $id]);
        $pagination = $paginator->paginate($quotes, $request->query->getInt('page', 1), 10);

        return $this->render('quote/index.html.twig', [
            'quotes' => $pagination,
            'categories' => $categories,
            'categorie' => $categorie,
        ]);
    }

    #[Route('/categorie/new', name: 'categorie_new')]
    #[Security("is_granted('ROLE_USER')")]
    public function CategorieNew(Request $request): Response
    {
        $categories = $this->categorieRepository->findAll();

        return $this->render('category/new.html.twig', ['case' => 'AJOUT', 'categories' => $categories], );
    }

    #[Route('/categorie/add', name: 'categorie_add')]
    public function categorieAdd(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $name = $request->request->get('name');

        $newCategory = new Category();
        $newCategory->setName($name);
        $em->persist($newCategory);
        $em->flush();

        return $this->redirectToRoute('quote_index');
    }

    #[Route('/categorie/{id}/edit', name: 'categorie_edit')]
    #[Security("is_granted('ROLE_USER')")]
    public function categorieEdit(Request $request, $id): Response
    {
        $categories = $this->categorieRepository->findAll();

        return $this->render('category/new.html.twig', ['case' => 'EDIT', 'id' => $id, 'categories' => $categories]);
    }

    #[Route('/categorie/{id}/modify/', name: 'categorie_modify')]
    public function categorieModify(Request $request, $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $name = $request->request->get('name');
        //dd($name);

        $categorie = $this->categorieRepository->findOneBy(['id' => $id]);
        $categorie->setName($name);
        $em->persist($categorie);
        $em->flush();

        return $this->redirectToRoute('quote_index');
    }

    #[Route('/categorie/{id}/delete', name: 'categorie_delete')]
    #[Security("is_granted('ROLE_USER')")]
    public function categorieDelete(Request $request, $id): Response
    {
        $categorie = $this->categorieRepository->findOneBy(['id' => $id]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();

        return $this->redirectToRoute('quote_index');
    }
}
