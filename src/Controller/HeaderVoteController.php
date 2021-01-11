<?php

namespace App\Controller;

use App\Entity\HeaderVote;
use App\Form\HeaderVoteType;
use App\Repository\HeaderVoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/header/vote")
 */
class HeaderVoteController extends AbstractController
{
    /**
     * @Route("/", name="header_vote_index", methods={"GET"})
     */
    public function index(HeaderVoteRepository $headerVoteRepository): Response
    {
        return $this->render('header_vote/index.html.twig', [
            'header_votes' => $headerVoteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="header_vote_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $headerVote = new HeaderVote();
        $form = $this->createForm(HeaderVoteType::class, $headerVote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($headerVote);
            $entityManager->flush();

            return $this->redirectToRoute('header_vote_index');
        }

        return $this->render('header_vote/new.html.twig', [
            'header_vote' => $headerVote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="header_vote_show", methods={"GET"})
     */
    public function show(HeaderVote $headerVote): Response
    {
        return $this->render('header_vote/show.html.twig', [
            'header_vote' => $headerVote,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="header_vote_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, HeaderVote $headerVote): Response
    {
        $form = $this->createForm(HeaderVoteType::class, $headerVote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('header_vote_index');
        }

        return $this->render('header_vote/edit.html.twig', [
            'header_vote' => $headerVote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="header_vote_delete", methods={"DELETE"})
     */
    public function delete(Request $request, HeaderVote $headerVote): Response
    {
        if ($this->isCsrfTokenValid('delete'.$headerVote->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($headerVote);
            $entityManager->flush();
        }

        return $this->redirectToRoute('header_vote_index');
    }
}
