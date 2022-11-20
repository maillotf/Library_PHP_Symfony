<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class BookController extends AbstractController
{
    #[Route('/books', name: 'books_all', methods: ['GET'])]
    public function getBooks(SerializerInterface $serializer, BookRepository $bookRepository): JsonResponse
    {
        $books = $bookRepository->findAll();
        $serializedBooks = $serializer->serialize($books, 'json', [
            'groups'=> ['book']
        ]);
        return JsonResponse::fromJsonString($serializedBooks, 200);
    }

    #[Route('/books/author/{authorId}', name: 'books_by_author', methods: ['GET'])]
    public function getBooksByAuthor(SerializerInterface $serializer, BookRepository $bookRepository, int $authorId): JsonResponse
    {
        $books = $bookRepository->findByAuthor($authorId);

        $serializedBooks = $serializer->serialize($books, 'json', [
            'groups'=> ['book']
        ]);
        return JsonResponse::fromJsonString($serializedBooks, 200);
    }

    #[Route('/book/{bookId}', name: 'book_by_id', methods: ['GET'])]
    public function getBookById(SerializerInterface $serializer, BookRepository $bookRepository, int $bookId): JsonResponse
    {
        if (($book = $bookRepository->findOneById($bookId)) === null)
            return $this->json(null, 404);

        $serializedBook = $serializer->serialize($book, 'json', [
            
            'groups'=> ['bookAuthor']
        ]);
        
        return JsonResponse::fromJsonString($serializedBook, 200);
    }

    #[Route('/book', name: 'book_create', methods: ['POST'])]
    public function createBook(Request $request, ManagerRegistry $doctrine, SerializerInterface $serializer): JsonResponse
    {
        $book = $serializer->deserialize($request->getContent(), Book::class, 'json');

        $em = $doctrine->getManager();
        $authorReference = $em->getReference(Author::class, $book->getAuthor()->getId());
        $book->setAuthor($authorReference);

        try {
            $em->persist($book);
            $em->flush();
        }
        catch (UniqueConstraintViolationException $th) {
            return $this->json('Conflic : Traget resource already exist', 409);
        }
        catch (ForeignKeyConstraintViolationException $th)
        {
            return $this->json('Author not found', 400);
        }

        return $this->json(null, 201, array('Location' => '/book/' . $book->getId()));
    }

    #[Route('/book/{bookId}', name: 'book_delete', methods: ['DELETE'])]
    public function deleteBook(BookRepository $bookRepository, int $bookId): JsonResponse
    {
        $book = $bookRepository->findOneById($bookId);
        if ($book === null)
            return $this->json(null, 404);
        else
            $bookRepository->remove($book, true);

        return $this->json(null, 204);
    }
}
