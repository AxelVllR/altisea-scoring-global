<?php

namespace App\Controller;

use App\Repository\CompetitionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{

    private $em, $cRepo;

    public function __construct(EntityManagerInterface $em, CompetitionRepository $cRepo) {
        $this->em = $em;
        $this->cRepo = $cRepo;
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        /*$active_competition = $this->cRepo->findOneBy(["is_active" => true]);
        if($active_competition) {
            return $this->redirectToRoute("app_launch_competition", ["id" => $active_competition->getId()]);
        }*/

        $competitions = $this->cRepo->findAll();

        return $this->render('dashboard/index.html.twig', [
            'active_page' => 'dashboard',
            'competitions' => $competitions
        ]);
    }
}
