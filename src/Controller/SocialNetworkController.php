<?php

namespace App\Controller;

use App\Entity\SocialNetwork;
use App\Form\SocialNetworkType;
use App\Repository\SocialNetworkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/social/network")
 */
class SocialNetworkController extends AbstractController
{
    /**
     * @Route("/", name="social_network_index", methods={"GET"})
     */
    public function index(SocialNetworkRepository $socialNetworkRepository): Response
    {
        return $this->render('social_network/index.html.twig', [
            'social_networks' => $socialNetworkRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="social_network_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $socialNetwork = new SocialNetwork();
        $form = $this->createForm(SocialNetworkType::class, $socialNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($socialNetwork);
            $entityManager->flush();

            return $this->redirectToRoute('social_network_index');
        }

        return $this->render('social_network/new.html.twig', [
            'social_network' => $socialNetwork,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="social_network_show", methods={"GET"})
     */
    public function show(SocialNetwork $socialNetwork): Response
    {
        return $this->render('social_network/show.html.twig', [
            'social_network' => $socialNetwork,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="social_network_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SocialNetwork $socialNetwork): Response
    {
        $form = $this->createForm(SocialNetworkType::class, $socialNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('social_network_index');
        }

        return $this->render('social_network/edit.html.twig', [
            'social_network' => $socialNetwork,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="social_network_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SocialNetwork $socialNetwork): Response
    {
        if ($this->isCsrfTokenValid('delete'.$socialNetwork->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($socialNetwork);
            $entityManager->flush();
        }

        return $this->redirectToRoute('social_network_index');
    }
}
