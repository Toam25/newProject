<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BoutiqueRepository;
use App\Service\TypeOptionMenuService;
use App\Service\UtilsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
