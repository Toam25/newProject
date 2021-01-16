<?php

namespace App\Controller;

use App\Entity\Reference;
use App\Form\ReferenceType;
use App\Repository\BoutiqueRepository;
use App\Repository\ReferenceRepository;
use App\Service\InsertFileServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/reference")
 */
class ReferenceController extends AbstractController
{
   
    /**
     * @Route("/new", name="reference_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $reference = new Reference();
        $form = $this->createForm(ReferenceType::class, $reference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reference);
            $entityManager->flush();

            return $this->redirectToRoute('reference_index');
        }

        return $this->render('reference/new.html.twig', [
            'reference' => $reference,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reference_show", methods={"GET"})
     */
    public function show(Reference $reference): Response
    {
        return $this->render('reference/show.html.twig', [
            'reference' => $reference,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="reference_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reference $reference, BoutiqueRepository $boutiqueRepository,InsertFileServices $insertFileServices): Response
    {  
        $boutique= $boutiqueRepository->findOneBy(['user'=>$this->getUser()]);
        $image=$reference->getImages();
        $form = $this->createForm(ReferenceType::class, $reference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($reference->getPhotos()){
                $file=$insertFileServices->insertFile($reference->getPhotos(),['jpg,gif','jpeg','png']);
                $reference->setImages($file);
            }
            else{
                $reference->setImages($image);
            }

            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(['status'=>'success'],200);
        }

        return $this->render('admin/index.html.twig', [
            'pages'=> 'edit_reference',
             'boutique'=> $boutique,
            'form' => $form->createView(),
            'reference'=>$reference
        ]);
    }

    /**
     * @Route("/{id}", name="reference_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Reference $reference): Response
    {

        if ($this->getUser()) {
            $entityManager = $this->getDoctrine()->getManager();
            unlink('images/'.$reference->getImages()) ;
            $entityManager->remove($reference);
            $entityManager->flush();

            return new JsonResponse(['status'=>'success'], Response::HTTP_OK);
        }

        return new JsonResponse(['status'=>'error'], Response::HTTP_UNAUTHORIZED);

    }
}
