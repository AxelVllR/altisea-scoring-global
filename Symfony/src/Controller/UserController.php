<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\HydrateService;
use App\Services\RequestService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $serializer, $em;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $em) {
        $this->serializer = $serializer;
        $this->em = $em;
    }

    #[Route('/profile', name: 'api_user_profile', methods:['GET'])]
    public function index(): Response
    {
        $user = $this->serializer->serialize($this->getUser(), 'json'); 
        return new Response($user);
    }
}
