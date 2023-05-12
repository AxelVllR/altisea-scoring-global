<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\RequestService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    private $passwordHasher, $em, $requestService;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em)
    {   
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/register', name: 'api_register', methods:['POST'])]
    public function index(Request $request): Response
    {
        $req = new RequestService($request);
        $user = (new User())
            ->setEmail($req->get('username'));
        $user->setPassword($this->passwordHasher->hashPassword($user, $req->get('password')));
        $this->em->persist($user);
        $this->em->flush();
        return new Response('', 201);
    }
}
