<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Entity\User;
use App\Form\CompetitionType;
use App\Repository\CompetitionRepository;
use App\Repository\WodRepository;
use App\Services\HydrateService;
use App\Services\RequestService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompetitionController extends AbstractController
{
    private $serializer, $em, $cRepo, $wRepo;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $em, CompetitionRepository $cRepo, WodRepository $wRepo) {
        $this->serializer = $serializer;
        $this->em = $em;
        $this->cRepo = $cRepo;
        $this->wRepo = $wRepo;
    }

    #[Route('/competition', name: 'api_get_competition_active', methods:['GET'])]
    public function getActiveCompetition(): Response
    {
        $ret = $this->cRepo->findOneBy(["is_active" => true]);
        $competition = $this->serializer->serialize($ret, 'json', ["groups" => ["competition"]]); 

        return new Response($competition);
    }

    #[Route('/dashboard/competition/{id}', name: 'app_get_competition', methods:['GET'])]
    public function getAppCompetition($id, Request $request): Response
    {
        $ret = $this->cRepo->findOneBy(["id" => $id]);
        $competition = $this->serializer->serialize($ret, 'json', ["groups" => ["competition"]]); 
        $wod_in_progress = $ret->getWodInProgress();
        $wods = $this->wRepo->findBy(["Competition" => $ret]);
        $wods_list = $this->serializer->serialize($wods, 'json', ["groups" => ["competition"]]); 

        
        $wod_in_progress_formatted = $this->serializer->serialize($wod_in_progress, 'json', ["groups" => ["competition"]]);
        return new Response(json_encode([
            "competition" => $competition,
            "wods" => $wods_list,
            "in_progress" => $wod_in_progress_formatted
        ]));
    }

    #[Route('/dashboard/competition_active', name: 'app_get_active_competition', methods:['GET'])]
    public function getAppActiveCompetition(Request $request): Response
    {
        $ret = $this->cRepo->findOneBy(["is_active" => true]);
        if($ret) {
            $competition = $this->serializer->serialize($ret, 'json', ["groups" => ["competition"]]); 
            $wod_in_progress = $ret->getWodInProgress();
            $wods = $this->wRepo->findBy(["Competition" => $ret]);
            $wods_list = $this->serializer->serialize($wods, 'json', ["groups" => ["competition"]]); 

            
            $wod_in_progress_formatted = $this->serializer->serialize($wod_in_progress, 'json', ["groups" => ["competition"]]);
            return new Response(json_encode([
                "competition" => $competition,
                "wods" => $wods_list,
                "in_progress" => $wod_in_progress_formatted
            ]));
        }

        return new Response(null, 200);
    }

    #[Route('/dashboard/deactivate_competition/{id}', name: 'app_stop_competition', methods:['GET'])]
    public function stopCompetition($id, Request $request): Response
    {
        $competition = $this->cRepo->findOneBy(["id" => $id, "is_active" => true]);
        if($competition) {
            $competition->setIsActive(false);
            $competition->setWodInProgress(null);
            $this->em->persist($competition);
            $this->em->flush();
        }
        
        return new Response('ok', Response::HTTP_OK);
    }

    #[Route('/dashboard/activate_competition/{id}', name: 'app_start_competition', methods:['GET'])]
    public function startCompetition($id, Request $request): Response
    {
        $competitions = $this->cRepo->findBy(["is_active" => true]);
        foreach($competitions as $competition) {
            $competition->setIsActive(false);
            $this->em->persist($competition);
            $this->em->flush();
        }

        $competition = $this->cRepo->findOneBy(["id" => $id]);
        if($competition) {
            $competition->setIsActive(true);
            $this->em->persist($competition);
            $this->em->flush();
        }
        
        return new Response('ok', Response::HTTP_OK);
    }

    #[Route('/dashboard/competition/edit/{id}', name: 'app_edit_competition')]
    public function editCompetition($id, Request $request): Response
    {
        $competition = $this->cRepo->findOneBy(["id" => $id]);
        if(empty($competition)) {
            return $this->redirectToRoute('app_dashboard');
        }
        $form = $this->createForm(CompetitionType::class, $competition);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $this->em->persist($competition);
            $this->em->flush();

            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_dashboard');
        }

        $wods = $this->wRepo->findBy(["Competition" => $competition]);

        return $this->render('dashboard/competition_edit.html.twig', [
            'active_page' => 'dashboard',
            'form' => $form->createView(),
            'competition' => $competition,
            "wods" => $wods
        ]);
    }

    #[Route('/dashboard/competition/launch/{id}', name: 'app_launch_competition')]
    public function launchCompetition($id, Request $request): Response
    {
        $competition = $this->cRepo->findOneBy(["id" => $id]);
        if(empty($competition)) {
            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('dashboard/competition_launch.html.twig', [
            'active_page' => 'dashboard',
            'competition' => $competition,

        ]);
    }

    #[Route('/dashboard/competition_display', name: 'app_display_competition')]
    public function displayCompetition(Request $request): Response
    {
        return $this->render('dashboard/competition_display.html.twig', [
        ]);
    }

    #[Route('/dashboard/competition/add', name: 'app_add_competition')]
    public function addCompetition(Request $request): Response
    {
        $competition = new Competition();

        $form = $this->createForm(CompetitionType::class, $competition);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $this->em->persist($competition);
            $this->em->flush();

            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_dashboard');
        }


        return $this->render('dashboard/competition_add.html.twig', [
            'active_page' => 'dashboard',
            'form' => $form->createView(),
        ]);
    }
}
