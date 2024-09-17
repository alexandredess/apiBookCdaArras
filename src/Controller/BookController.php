<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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

    #[Route(path: '/api/books/{id}', name: 'deleteBook', methods: ['DELETE'])]
    public function deleteBook(Book $book, EntityManagerInterface $entityManager): JsonResponse
    {
    //supprimer le livre
        $entityManager->remove(object: $book);
    //exécuter la requête
        $entityManager->flush();
    //retourner une réponse vide
        return new JsonResponse(data: null, status: Response::HTTP_NO_CONTENT);


    }

    //créer un livre
    #[Route('/api/books', name:"createBook", methods: ['POST'])]
    public function createBook(SerializerInterface $serializer, EntityManagerInterface $em,Request $request, UrlGeneratorInterface $urlGenerator,AuthorRepository $authorRepository): JsonResponse
    {
        //désérialiser le contenu de la requête en objet Book
        $book = $serializer->deserialize($request->getContent(),Book::class, 'json');
        //Recupérer le contenu de la requête en tableau
        $content = $request->toArray();
        //récupérer l'id de l'auteur
        $idAuthor = $content['author'] ?? -1;
        //récupérer l'auteur par son id
        $book->setAuthor($authorRepository->find($idAuthor));
        //persister le livre
        $em->persist($book);
        //exécuter la requête
        $em->flush();
        
        //générer l'URL du livre créé
        $jsonBook = $serializer->serialize($book, 'json', ['groups'=> 'getBooks']);
        $location = $urlGenerator->generate('detailBook', ['id' =>$book->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        //persister le livre
        return new JsonResponse($jsonBook, Response::HTTP_CREATED,["Location" => $location], true);
    }
}
