<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use App\Repository\BoutiqueRepository;
use App\Service\TypeOptionMenuService;
use App\Service\UtilsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $blog= $blogRepository->findOneBy(['boutique'=>$boutique,'id'=>$id ]);
        $blogForm = $this->createForm(BlogType::class,$blog);

        if($blogForm->isSubmitted() and $blogForm->isValid() and $request->isXmlHttpRequest()){
            $em= $this->getDoctrine()->getManager();
            $em->flush($blog);
        }
    
        return $this->render('admin/index.html.twig', [
            'pages' => 'blogEdit',
            'boutique' => $boutique,
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
}
