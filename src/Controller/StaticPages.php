<?php
namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Repository\FormatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class StaticPages extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home.html.twig', [
            'titre' => 'Bienvenue sur Book Haven, la destination livre'
        ]);
    }

    #[Route('/apropos', name: 'apropos')]
    public function apropos(): Response
    {
        return $this->render('apropos.html.twig', [
            'titre' => 'À propos de Book Haven'
        ]);
    }

    #[Route('/cgv', name: 'cgv')]
    public function cgv(): Response
    {
        return $this->render('cgv.html.twig', [
            'titre' => 'Conditions Générales de Ventes'
        ]);
    }

    #[Route('/admin', name: 'admin')]
    public function admin(BookRepository $bookRepository, AuthorRepository $authorRepository, FormatRepository $formatRepository, CategoryRepository $categoryRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('You must be logged in to access this page.');
        }

        $book = $bookRepository->findAll();
        $author = $authorRepository->findAll();
        $format = $formatRepository->findAll();
        $category = $categoryRepository->findAll();

        return $this->render('admin.html.twig', [
            'titre' => 'Portail administrateur',
            'books' => $book,
            'authors' => $author,
            'formats' => $format,
            'categories' => $category,
        ]);
    }

}