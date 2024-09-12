<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthorController extends AbstractController
{
    #[Route(path: '/api/authors', name: 'app_author')]
    public function getAuthor(AuthorRepository $authorRepository, SerializerInterface $serializer): JsonResponse
    {
        //récupérer la liste des livres
        $authorList = $authorRepository->findAll();

        //sérialiser la liste des livres en format JSON
        $jsonAuthorList = $serializer->serialize(data: $authorList,format: 'json',context: ['groups' => 'getBooks', 'getAuthors']);
        
        //retourner la liste des livres en format JSON
        return new JsonResponse(data: $jsonAuthorList, status: Response::HTTP_OK, headers: [], json: true);
    }

    #[Route(path: '/api/authors/{id}', name: 'detailAuthor', methods: ['GET'])]
    public function getDetailAuthor(int $id, SerializerInterface $serializer,AuthorRepository $authorRepository):JsonResponse{
        //récupérer l'auteur par son id
        $author = $authorRepository->find(id: $id);
        if ($author){
            $jsonAuthor = $serializer->serialize(data: $author,format: 'json',context: ['groups' => 'getBooks']);
            return new JsonResponse(data: $jsonAuthor, status: Response::HTTP_OK,headers:[],json: true);
        }


        return new JsonResponse(data: null, status: Response::HTTP_NOT_FOUND);
    }
}
