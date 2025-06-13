<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Format;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\CartRepository;
use App\Repository\CategoryRepository;
use App\Repository\FormatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/', name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository, Request $request, CategoryRepository $categoryRepository, AuthorRepository $authorRepository, FormatRepository $formatRepository): Response
    {
        $category = $request->query->get('category');
        $format = $request->query->get('format');
        $author = $request->query->get('author');

        if ($category) {
            $category = $categoryRepository->find($category);
            $books = $category->getBooks();
            $categoryName = $category->getName();
            $authorName = null;
            $formatName = null;
        } elseif ($format) {
            $format = $formatRepository->find($format);
            $books = $format->getBooks();
            $formatName = $format->getName();
            $categoryName = null;
            $authorName = null;
        } elseif ($author) {
            $author = $authorRepository->find($author);
            $books = $author->getBooks();
            $authorName = $author->getName();
            $categoryName = null;
            $formatName = null;
        } else {
            $books = $bookRepository->findAll();
            $categoryName = null;
            $formatName = null;
            $authorName = null;
        }

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'categories' => $categoryRepository->findAll(),
            'formats' => $formatRepository->findAll(),
            'authors' => $authorRepository->findAll(),
            'categoryName' => $categoryName,
            'formatName' => $formatName,
            'authorName' => $authorName,
        ]);
    }

    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('fichier-image')->getData();

            if ($image) {
                $book->setPicture("tmp");
                $entityManager->persist($book);
                $entityManager->flush();
                $filename = 'image-'.$book->getId().'.'.$image->guessExtension();
                $book->setPicture($filename);
                $image->move('uploads', $filename);
            }

            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_show', methods: ['GET'])]
    public function show(Book $book, Author $author, CartRepository $cartRepository): Response
    {
        $categories = $book->getCategory();
        $formats = $book->getFormat();
        $count = $cartRepository->countBookInCart($book->getId());

        return $this->render('book/show.html.twig', [
            'book' => $book,
            'author' => $author,
            'categories' => $categories,
            'formats' => $formats,
            'count' => $count,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('fichier-image')->getData();

            if ($image) {
                if (file_exists('uploads/' . $book->getPicture()))
                    unlink('uploads/' . $book->getPicture());

                $filename = 'image-'.$book->getId().'.'.$image->guessExtension();
                $book->setPicture($filename);
                $image->move('uploads', $filename);
            }
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            if (file_exists('uploads/' . $book->getPicture()))
                unlink('uploads/' . $book->getPicture());

            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
    }
}
