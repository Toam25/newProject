<?php

namespace App\Controller;

use App\Entity\UserCondition;
use App\Form\UserConditionType;
use App\Repository\BoutiqueRepository;
use App\Repository\UserConditionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/user/condition")
 */
class UserConditionController extends AbstractController
{
    /**
     * @Route("/", name="user_condition_index", methods={"GET"})
     */
    public function index(UserConditionRepository $userConditionRepository): Response
    {
        return $this->render('user_condition/index.html.twig', [
            'user_conditions' => $userConditionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_condition_new", methods={"GET","POST"})
     */
    public function new(BoutiqueRepository $boutiqueRepository, Request $request): Response
    {
        $userCondition = new UserCondition();
        $form = $this->createForm(UserConditionType::class, $userCondition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userCondition->setBoutique($boutiqueRepository->findOneBy(['user'=>$this->getUser()]));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userCondition);
            $entityManager->flush();

            return $this->redirectToRoute('user_condition_index');
        }

        return $this->render('user_condition/new.html.twig', [
            'user_condition' => $userCondition,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_condition_show", methods={"GET"})
     */
    public function show(UserCondition $userCondition): Response
    {
        return $this->render('user_condition/show.html.twig', [
            'user_condition' => $userCondition,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_condition_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserCondition $userCondition): Response
    {
        $form = $this->createForm(UserConditionType::class, $userCondition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_condition_index');
        }

        return $this->render('user_condition/edit.html.twig', [
            'user_condition' => $userCondition,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_condition_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserCondition $userCondition): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userCondition->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userCondition);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_condition_index');
    }
}
