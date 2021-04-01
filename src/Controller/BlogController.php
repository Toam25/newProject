<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use App\Repository\BoutiqueRepository;
use App\Service\TypeOptionMenuService;
use App\Service\UtilsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{   
    private $typeOptionMenuService;
    private $utilsService;
    public function __construct(UtilsService $utilsService, TypeOptionMenuService $typeOptionMenuService )
    {
        $this->typeOptionMenuService=$typeOptionMenuService;
        $this->utilsService=$utilsService;
    }
      
    /**
     * @Route("/addBlog", name="addBlog")
     */
    public function addBlog(BoutiqueRepository $boutiqueRepository)
    {

        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );

        $blogForm = $this->createForm(BlogType::class,new Blog);
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        return $this->render('admin/index.html.twig', [
            'pages' => 'blogAdd',
            'boutique' => $boutique,
            'blogForm'=>$blogForm->createView()
        ]);
    }

    /**
     * @Route("/edit/blog/{id}", name="editBlog")
     */
    public function editBlog(BlogRepository $blogRepository,$id, BoutiqueRepository $boutiqueRepository,Request $request)
    {

        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        
        if(in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles())){
            $blog= $blogRepository->findOneBy(['id'=>$id ]);
        }
        else{
            $blog= $blogRepository->findOneBy(['boutique'=>$boutique,'id'=>$id ]);
        }
        $blogForm = $this->createForm(BlogType::class,$blog);
        
        if($blogForm->isSubmitted() and $request->isXmlHttpRequest()){
            $em= $this->getDoctrine()->getManager();
            dd($blog);
            $em->persist($blog);
            $em->flush();
            return new JsonResponse(['status'=>"success"]);
        }
    
        return $this->render('admin/index.html.twig', [
            'pages' => 'blogEdit',
            'boutique' => $boutique,
            'id_blog'=>$blog->getId(),
            'blog'=>$blog,
            'blogForm'=>$blogForm->createView()
        ]);
    }
    /**
     * @Route("/list/Blog", name="listBlog")
     */
    public function listBlog(BoutiqueRepository $boutiqueRepository,BlogRepository $blogRepository)
    {

        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );



        $blogForm = $this->createForm(BlogType::class,new Blog);
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        return $this->render('admin/index.html.twig', [
            'pages' => 'bloglist',
            'boutique' => $boutique,
            'blogs'=>$blogRepository->findAllArticleByBoutique($boutique)
            //'blogForm'=>$blogForm->createView()
        ]);
    }
    /**
     * @Route("/preview/blog/{id}", name="previewBlog")
     */
    public function previewBlog(BoutiqueRepository $boutiqueRepository,Blog $blog)
    {
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        return $this->render('admin/index.html.twig', [
            'pages' => 'blogpreview',
            'boutique' => $boutique,
            'blog'=>$blog
        ]);
    }
}
