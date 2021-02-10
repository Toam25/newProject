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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
{
    /**
     * @Route("/admin/list", name="article_liste", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository,BoutiqueRepository $boutiqueRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'articles' => $articleRepository->findAll(),
            'pages' => 'list',
            'boutique' => $boutiqueRepository->findOneBy(['user'=>$this->getUser()])
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
        $images = $form->get('images')->getData();
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();


            foreach ($images as $image) {
                $fichier = $insertFileServices->insertFile($image);
                $img = new Images();
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
            'boutique' => $boutique,
            'form' => $form->createView(),
            'pages' => 'new'
        ]);
    }

    /**
     * @Route("/admin/article/{id}", name="article_show", methods={"GET","POST"})
     */
    public function show($id,Request $request,InsertFileServices $insertFileServices, ArticleRepository $articleRepository, BoutiqueRepository $boutiqueRepository): Response
    {   
        $boutique = $boutiqueRepository->findOneBy(['user'=>$this->getUser()]);
        $article= $articleRepository->findOneArticleByBoutiqueWithImage($id, $boutique);
        $form=$this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            if($form->get('images')->getData()!=null){
                $images=$form->get('images')->getData();
                $newfile = $insertFileServices->insertFile($images[0],['png','jpeg','jpg','gif']);
                $image=$article->getImages()[0];
                $file=$image->getName();
                $image->setName($newfile);
                unlink('images/'.$file);
                $this->getDoctrine()->getManager()->persist($image);
            }
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse(['status'=>'success'],Response::HTTP_OK);
        }
        return $this->render('admin/index.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'pages' => 'detail_article',
            'article'=>$article,
            'boutique'=>$boutique

        ]);
    }

    /**
     * @Route("/admin/article/edit/{id}", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article,BoutiqueRepository $boutiqueRepository ): Response
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
            'pages' => 'edit',
            'boutique' => $boutiqueRepository->findOneBy(['user'=>$this->getUser()])
        ]);
    }

    /**
     * @Route("/admin/article/delete/{id}", name="article_delete")
     */
    public function delete(Request $request, Article $article, ImagesRepository $imagesRepository, BoutiqueRepository $boutiqueRepository): Response
    {
        // if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
        $myboutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        $boutique = $article->getBoutique();
        if ($myboutique->getId() === $boutique->getId()) {
            $images = $imagesRepository->findBy(['article' => $article]);
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($images as $image) {
                $entityManager->remove($image);
                unlink('images/' . $image->getName());
            }
            $entityManager->remove($article);
            $entityManager->flush();
            return new JsonResponse(['status'=>'success'],Response::HTTP_OK);
        }
        else{
            return new JsonResponse(['status'=>'error'],Response::HTTP_UNAUTHORIZED);
        }
    }
}
