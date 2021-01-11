<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Images;
use App\Entity\Votes;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\ImagesRepository;
use App\Service\InsertFileServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
{
    /**
     * @Route("/admin/list", name="article_liste", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'articles' => $articleRepository->findAll(),
            'pages'=>'list'
        ]);
    }

    /**
     * @Route("/admin/new", name="article_new")
     */
    public function new(BoutiqueRepository $boutiqueRepository, Request $request, InsertFileServices $insertFileServices): Response
    {
        $article = new Article();
        
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        $images= $form->get('images')->getData();
        $boutique = $boutiqueRepository->findOneBy(['user'=>$this->getUser()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            
            
            foreach( $images as $image){
                $fichier = $insertFileServices->insertFile($image);
                $img= new Images();
                $img->setName($fichier);
                $article->addImage($img);

            }

           
            $article->setBoutique($boutique);
            
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_liste');
        }

        return $this->render('admin/index.html.twig', [
            'article' => $article,
            'boutique'=>$boutique,
            'form' => $form->createView(),
            'pages'=> 'new'
        ]);
    }

    /**
     * @Route("/admin/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('admin/index.html.twig', [
            'article' => $article,
            'pages'=> 'detail'
        ]);
    }

    /**
     * @Route("/admin/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_liste');
        }

        return $this->render('admin/index.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'pages'=> 'edit'
        ]);
    }

    /**
     * @Route("/admin/delete/{id}", name="article_delete")
     */
    public function delete(Request $request, Article $article, ImagesRepository $imagesRepository): Response
    {
       // if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $images = $imagesRepository->findBy(['article'=>$article]);
            $entityManager = $this->getDoctrine()->getManager();
           foreach ($images as $image) {
             $entityManager->remove($image);
           }
            $entityManager->remove($article);
            $entityManager->flush();
        

        return $this->redirectToRoute('article_liste');
    }
}
