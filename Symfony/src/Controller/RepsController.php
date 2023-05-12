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

class RepsController extends AbstractController
{
    private $serializer, $em;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $em) {
        $this->serializer = $serializer;
        $this->em = $em;
    }

    #[Route('/reps/add/{nb}', name: 'api_reps_add', methods:['POST'])]
    public function addReps($nb): Response
    {
        $user = $this->getUser();
        $user->setReps($user->getReps() + $nb);
        $this->em->persist($user);
        $this->em->flush();
        return new Response('ok');
    }
}
