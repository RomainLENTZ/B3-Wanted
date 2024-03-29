<?php

namespace App\Controller;

use App\Entity\Hunt;
use App\Entity\Target;
use App\Form\HuntType;
use App\Form\TargetType;
use App\Repository\HuntRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/target', name: 'app_target')]
class TargetController extends AbstractController
{
    #[Route('/', name: '_index_target')]
    public function index(): Response
    {
        if(!$this->isGranted('add', $this->getUser()))
        {
            return $this->redirectToRoute('app_access_denied');
        }

        $targetExemple = new Target();
        $targetExemple->setName("")
            ->setDescription("");

        $form = $this->createForm(TargetType::class, $targetExemple);
        return $this->render('target/index.html.twig', [
            'form' => $form,
            'message'=>null,
        ]);
    }

    #[Route('/add', name: '_add_target', methods: ["POST"])]
    public function add(Request $request, EntityManagerInterface $entityManager, HuntRepository $huntRepository): Response{
        if(!$this->isGranted('add', $this->getUser()))
        {
            return $this->redirectToRoute('app_access_denied');
        }

        $target = new Target();
        $targetForm = $this->createForm(TargetType::class, $target);
        $targetForm->handleRequest($request);

        if($targetForm->isSubmitted() && $targetForm->isValid()){
            if(!$huntRepository->findHuntIsOpenByTargetName($targetForm->getData()->getName()) != null){
                //dd("ok");
                $target = $targetForm->getData();
                $entityManager->persist($target);
                $entityManager->flush();
                $request->getSession()->getFlashBag()->set('targetId',$target->getId());
                return $this->redirectToRoute('app_hunt_form_hunt');
            }

            else{
                $targetExemple = new Target();
                $targetExemple->setName("")
                    ->setDescription("");

                $form = $this->createForm(TargetType::class, $targetExemple);
                return $this->render('target/index.html.twig', [
                    'message' => 'Une chasse ouverte concerne déja cette cible',
                    'form' => $form,
                ]);
            }

        }

        return $this->redirectToRoute('app_hunt_form_hunt');
    }

}
