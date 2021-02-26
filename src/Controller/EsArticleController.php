<?php

namespace App\Controller;

use App\Entity\EsArticle;
use App\Form\EsArticleType;
use App\Repository\ArticleRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\EsArticleRepository;
use App\Service\InsertFileServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("superadmin/es/article")
 */
class EsArticleController extends AbstractController
{
    /**
     * @Route("/{category}", name="es_article_list")
     */
    public function index($category,EsArticleRepository $esArticleRepository,InsertFileServices $insertFileServices, ArticleRepository $articleRepository,BoutiqueRepository $boutiqueRepository,Request $request): Response
    {   
        $boutique = $boutiqueRepository->findOneBy(['user'=>$this->getUser()]);
       
        $esArticle = new EsArticle();
        $form = $this->createForm(EsArticleType::class,$esArticle);
        $button_add_ess = ($category!=null) ? AdminController::button_add_boutique($category,"btn btn-success ajout_ess_article_ev") : " ";
        
        $form->handleRequest($request);
        if($form->isSubmitted()){
            
         
            $file = $insertFileServices->insertFile($esArticle->getPhotos());
            $esArticle->setCategory($category);
            $esArticle->setImage($file);
            $esArticle->setBoutique($boutique);
            $em=$this->getDoctrine()->getManager();
            $em->persist($esArticle);
            $em->flush();
            return new Response(json_encode(['id'=>$esArticle->getId(),'images'=>$esArticle->getImage(),'type'=>$esArticle->getType()]));
        }
        $allArticle = $articleRepository->findBy(['boutique'=>$boutique] );
        return $this->render('admin/index.html.twig', [
            'es_articles' => $esArticleRepository->findBy(['category'=>$category,'boutique'=>$boutique]),
            'pages'=>'list_es',
            'articles'=>$allArticle,
            'category'=>$category,
            'menu'=>AdminController::nameMenu($category),
            'button_add_es'=>$button_add_ess,
            'form'=>$form->createView(), 
        ]);
    }

    /**
     * @Route("/new", name="es_article_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $esArticle = new EsArticle();
        $form = $this->createForm(EsArticleType::class, $esArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($esArticle);
            $entityManager->flush();

            return $this->redirectToRoute('es_article_index');
        }

        return $this->render('es_article/new.html.twig', [
            'es_article' => $esArticle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="es_article_show", methods={"GET"})
     */
    public function show(EsArticle $esArticle): Response
    {
        return $this->render('es_article/show.html.twig', [
            'es_article' => $esArticle,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="es_article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EsArticle $esArticle): Response
    {
        $form = $this->createForm(EsArticleType::class, $esArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('es_article_index');
        }

        return $this->render('es_article/edit.html.twig', [
            'es_article' => $esArticle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="es_article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EsArticle $esArticle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$esArticle->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($esArticle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('es_article_index');
    }
}
