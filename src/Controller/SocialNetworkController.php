<?php

namespace App\Controller;

use App\Entity\SocialNetwork;
use App\Form\SocialNetworkType;
use App\Repository\BoutiqueRepository;
use App\Repository\SocialNetworkRepository;
use App\Service\InsertFileServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/social/network")
 */
class SocialNetworkController extends AbstractController
{
    /**
     * @Route("/", name="social_network_index", methods={"GET"})
     */
    public function index(SocialNetworkRepository $socialNetworkRepository,  BoutiqueRepository $boutiqueRepository): Response
    {
         $boutique=$boutiqueRepository->findOneBy(['user'=>$this->getUser()]);
        return $this->render('admin/index.html.twig', [
            'pages'=>'list_socialNetwork',
            'boutique'=>$boutique,
            'list_socialNetworks'=>$socialNetworkRepository->findBy(['boutique'=>$boutique])
        ]);
        
    }

    /**
     * @Route("/new", name="social_network_new", methods={"GET","POST"})
     */
    public function new(Request $request, InsertFileServices $insertFileServices, BoutiqueRepository $boutiqueRepository): Response
    {
        $socialNetwork = new SocialNetwork();
        $form = $this->createForm(SocialNetworkType::class, $socialNetwork);
        $form->handleRequest($request);
        $boutique= $boutiqueRepository->findOneBy(['user'=>$this->getUser()]);

        if ($form->isSubmitted() ) {
            
            $socialNetwork->setImages($insertFileServices->insertFile($socialNetwork->getPhotos(),['jpg,gif','jpeg','png']));
            $socialNetwork->setBoutique($boutique);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($socialNetwork);
            $entityManager->flush();
          
            return new JsonResponse([
                'status'=>'success',
                'id'=>$socialNetwork->getId(),
                'images'=>$socialNetwork->getImages(),
                'description'=>$socialNetwork->getDescription(),
                'nameLink'=>$socialNetwork->getNameLink(),
                'link'=>$socialNetwork->getLink()                
            ], Response::HTTP_OK);
        }

        return $this->render('admin/index.html.twig', [
            'pages'=>'add_socialNetwork',
            'form' => $form->createView(),
            'boutique'=>$boutique
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
    public function edit(Request $request,InsertFileServices $insertFileServices, SocialNetwork $socialNetwork,BoutiqueRepository $boutiqueRepository): Response
    {   
        $image=$socialNetwork->getImages();
        $form = $this->createForm(SocialNetworkType::class, $socialNetwork);
        $form->handleRequest($request);
        $boutique= $boutiqueRepository->findOneBy(['user'=>$this->getUser()]);

        if ($form->isSubmitted() && $form->isValid()) {
            if($socialNetwork->getPhotos()){
                $file=$insertFileServices->insertFile($socialNetwork->getPhotos(),['jpg,gif','jpeg','png']);
                $socialNetwork->setImages($file);
            }
            else{
                $socialNetwork->setImages($image);
            }

            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(['status'=>'success'],200);
        }

        return $this->render('admin/index.html.twig', [
            'pages'=>'edit_socialNetwork',
            'form' => $form->createView(),
            'socialNetwork'=>$socialNetwork,
            'boutique'=>$boutique
        ]);
    }

    /**
     * @Route("/delete/{id}", name="social_network_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SocialNetwork $socialNetwork): Response
    {
        if ($this->getUser()) {
            $entityManager = $this->getDoctrine()->getManager();
            unlink('images/'.$socialNetwork->getImages()) ;
            $entityManager->remove($socialNetwork);
            $entityManager->flush();

            return new JsonResponse(['status'=>'success'], Response::HTTP_OK);
        }

        return new JsonResponse(['status'=>'error'], Response::HTTP_UNAUTHORIZED);
    }
}
