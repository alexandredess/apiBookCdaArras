<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookController extends AbstractController
{
    #[Route(path: '/api/books', name: 'book',methods: ['GET'])]
    public function getBookList(BookRepository $bookRepository, SerializerInterface $serializer): JsonResponse
    {
        //récupérer la liste des livres
        $bookList = $bookRepository->findAll();

        //sérialiser la liste des livres en format JSON
        $jsonBookList = $serializer->serialize(data: $bookList,format: 'json');
        
        //retourner la liste des livres en format JSON
        return new JsonResponse(data: $jsonBookList, status: Response::HTTP_OK, headers: [], json: true);
    }
}
