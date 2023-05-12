<?php

namespace App\Controller;

use App\Entity\Wod;
use App\Form\WodType;
use App\Repository\CompetitionRepository;
use App\Repository\WodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class WodController extends AbstractController
{

    private $serializer, $em, $wRepo, $cRepo;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $em, WodRepository $wRepo, CompetitionRepository $cRepo) {
        $this->serializer = $serializer;
        $this->em = $em;
        $this->cRepo = $cRepo;
        $this->wRepo = $wRepo;
    }

    #[Route('/dashboard/wod/edit/{id}', name: 'app_edit_wod')]
    public function editCompetition($id, Request $request): Response
    {
        $wod = $this->wRepo->findOneBy(["id" => $id]);
        if(empty($wod)) {
            return $this->redirectToRoute('app_dashboard');
        }
        $form = $this->createForm(WodType::class, $wod);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $this->em->persist($wod);
            $this->em->flush();

            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_edit_competition', ["id" => $wod->getCompetition()->getId()]);
        }

        return $this->render('wod/wod_edit.html.twig', [
            'active_page' => 'dashboard',
            'form' => $form->createView(),
            'wod' => $wod
        ]);
    }

    #[Route('/dashboard/wod/add/{competitionId}', name: 'app_add_wod')]
    public function addCompetition($competitionId, Request $request): Response
    {
        $wod = new Wod();
        $competition = $this->cRepo->findOneBy(["id" => $competitionId]);
        if(empty($competition)) {
            return $this->redirectToRoute('app_dashboard');
        }
        $wod->setCompetition($competition);
        $form = $this->createForm(WodType::class, $wod);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $this->em->persist($wod);
            $this->em->flush();

            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_edit_competition', ["id" => $competitionId]);
        }


        return $this->render('wod/wod_add.html.twig', [
            'active_page' => 'dashboard',
            'form' => $form->createView(),
        ]);
    }
}
