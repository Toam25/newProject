<?php

namespace App\Controller;

use App\Entity\Vote;
use App\Form\VoteType;
use App\Repository\BoutiqueRepository;
use App\Repository\VoteRepository;
use App\Service\InsertFileServices;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/vote")
 */
class VoteController extends AbstractController
{
    /**
     * @Route("/list", name="vote_list", methods={"GET"})
     */
    public function index(VoteRepository $voteRepository, BoutiqueRepository $boutiqueRepository, Request $request, InsertFileServices $insertFileServices): Response
    {   
        $vote = new Vote();
        $form= $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boutique = $boutiqueRepository->findOneBy(['user'=>$this->getUser()]);
            $file=$insertFileServices->insertFile($vote->getPhotos());
            $vote->setImages($file);
            $vote->setBoutique($boutique);
            $vote->setCreateAt(new DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vote);
            $entityManager->flush();
            $array = [
                'status'=>'success',
                'images'=>$file
            ];
            return new JsonResponse($array,Response::HTTP_OK);
        }
        return $this->render('admin/index.html.twig', [
            'pages'=>'vote',
            'boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()]),
            'votes' => $voteRepository->findVoteWithHeaderBy($boutiqueRepository->findOneBy(['user'=>$this->getUser()])),
             'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/new", name="vote_new", methods={"GET","POST"})
     */
    public function new(Request $request, BoutiqueRepository $boutiqueRepository, InsertFileServices $insertFileServices): Response
    {
        $vote = new Vote();
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boutique = $boutiqueRepository->findOneBy(['user'=>$this->getUser()]);
            $vote->setImages($insertFileServices->insertFile($vote->getPhotos()));
            $vote->setBoutique($boutique);
            $vote->setCreateAt(new DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vote);
            $entityManager->flush();

            return $this->redirectToRoute('vote_index');
        }

        return $this->render('vote/new.html.twig', [
            'vote' => $vote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="vote_show", methods={"GET"})
     */
    public function show(Vote $vote): Response
    {
        return $this->render('vote/show.html.twig', [
            'vote' => $vote,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="vote_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Vote $vote): Response
    {
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('vote_index');
        }

        return $this->render('vote/edit.html.twig', [
            'vote' => $vote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="vote_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Vote $vote): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vote->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($vote);
            $entityManager->flush();
        }

        return $this->redirectToRoute('vote_index');
    }
}
