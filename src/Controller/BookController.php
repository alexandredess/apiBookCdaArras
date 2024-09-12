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
        $jsonBookList = $serializer->serialize(data: $bookList,format: 'json',context: ['groups' => 'getBooks']);
        
        //retourner la liste des livres en format JSON
        return new JsonResponse(data: $jsonBookList, status: Response::HTTP_OK, headers: [], json: true);
    }

    #[Route(path: '/api/books/{id}', name: 'detailBook', methods: ['GET'])]
    public function getDetailBook(int $id, SerializerInterface $serializer,BookRepository $bookRepository):JsonResponse{
        //récupérer le livre par son id
        $book = $bookRepository->find(id: $id);
        if ($book){
            $jsonBook = $serializer->serialize(data: $book,format: 'json',context: ['groups' => 'getBooks']);
            return new JsonResponse(data: $jsonBook, status: Response::HTTP_OK,headers:[],json: true);
        }


        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
