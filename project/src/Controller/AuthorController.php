<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'author_create', methods:['POST'])]
    public function createAuthor(Request $request, SerializerInterface $serializer, AuthorRepository $authorRepository): JsonResponse
    {
        $author = $serializer->deserialize($request->getContent(), Author::class, 'json');

        try {
            $authorRepository->save($author, true);
        } 
        catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $th) {
            return $this->json('Conflic : Traget resource already exist', 409);//https://www.rfc-editor.org/rfc/rfc9110
        }
        
        return $this->json($author, 201, array('Location' => '/author/' . $author->getId()));
    }

    #[Route('/authors', name: 'authors_all', methods:['GET'])]
    public function getAuthors(SerializerInterface $serializer, AuthorRepository $authorRepository): JsonResponse
    {
        $authors = $authorRepository->findAll();
        $serializedAuthors = $serializer->serialize($authors, 'json', [
            'circular_reference_handler' => function ($object) { return $object->getId(); }
        ]);
        return JsonResponse::fromJsonString($serializedAuthors, 200);
    }

    #[Route('/author/{authorId}', name: 'author_delete', methods:['DELETE'])]
    public function deleteAuthor(AuthorRepository $authorRepository, int $authorId): JsonResponse
    {
        $author = $authorRepository->findOneById($authorId);
        if ($author === null)
            return $this->json(null, 404);
        else
            $authorRepository->remove($author, true);
        
        return $this->json(null, 204);//202 if queued
    }
}
